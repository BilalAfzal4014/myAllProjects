<?php

namespace App\Libraries;

use App\Components\CompanyAttributeData;
use App\EmailList;

class VerifyEmail
{
    private $company_id;

    /**
     * VerifyEmail constructor.
     * @param $company_id
     */
    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * Verify an email address through DNS records & server ping.
     *
     * @param string $to
     *
     * @return array
     */
    private function verifyAndProcess($to)
    {
        $from = config('mail.from.address');
        $response = [];

        // Get the domain of the email recipient
        $email_arr = explode('@', $to);
        $domain = array_slice($email_arr, -1);
        $domain = $domain[0];

        // Trim [ and ] from beginning and end of domain string, respectively
        $domain = ltrim($domain, '[');
        $domain = rtrim($domain, ']');

        if ('IPv6:' == substr($domain, 0, strlen('IPv6:'))) {
            $domain = substr($domain, strlen('IPv6') + 1);
        }

        $mxhosts = array();
        // Check if the domain has an IP address assigned to it
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            $mx_ip = $domain;
        } else {
            // If no IP assigned, get the MX records for the host name
            getmxrr($domain, $mxhosts, $mxweight);
        }

        if (!empty($mxhosts)) {
            $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
        } else {
            // If MX records not found, get the A DNS records for the host
            if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $record_a = dns_get_record($domain, DNS_A);
                // else get the AAAA IPv6 address record
            } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $record_a = dns_get_record($domain, DNS_AAAA);
            }

            if (!empty($record_a)) {
                $mx_ip = $record_a[0]['ip'];
            } else {
                // Exit the program if no MX records are found for the domain host
                $result = 'invalid';
                $details = 'No suitable MX records found.';

                return [$result, $details];
            }
        }

        // Open a socket connection with the hostname, smtp port 25
        $connect = @fsockopen($mx_ip, 25);

        if ($connect) {

            // Initiate the Mail Sending SMTP transaction
            if (preg_match('/^220/i', $out = fgets($connect, 1024))) {

                // Send the HELO command to the SMTP server
                fputs($connect, "HELO $mx_ip\r\n");
                $out = fgets($connect, 1024);
                $details = $out."\n";

                // Send an SMTP Mail command from the sender's email address
                fputs($connect, "MAIL FROM: <$from>\r\n");
                $from = fgets($connect, 1024);
                $details .= $from."\n";

                // Send the SCPT command with the recepient's email address
                fputs($connect, "RCPT TO: <$to>\r\n");
                $to = fgets($connect, 1024);
                $details .= $to."\n";

                // Close the socket connection with QUIT command to the SMTP server
                fputs($connect, 'QUIT');
                fclose($connect);

                // The expected response is 250 if the email is valid
                if (!preg_match('/^250/i', $from) || !preg_match('/^250/i', $to)) {
                    $result = 'invalid';
                } else {
                    $result = 'valid';
                }
            } else {
                $result = 'invalid';
                $details = 'Could not connect to server';
            }
        } else {
            $result = 'invalid';
            $details = 'Could not connect to server';
        }

        return [$result, $details];
    }

    /**
     * Verify an email.
     *
     * @param string $email
     *
     * @return bool
     */
    public function verify($email)
    {
        $verify_email = config('engagement.message.verify_email');
        if (isset($verify_email) && ((bool)$verify_email === false)) {
            return false;
        }

        try {
            $emailItem = EmailList::where([
                ['company_id', $this->company_id],
                ['email', $email]
            ])->firstOrFail();
        } catch (\Exception $exception) {
        }

        if (empty($emailItem)) {
            $response = $this->verifyAndProcess($email);
            $status = in_array(strtolower($response[0]), ['invalid']) ? EmailList::STATUS_BLACKLIST : EmailList::STATUS_WHITELIST;

            $emailItem = new EmailList();
            $emailItem->company_id = $this->company_id;
            $emailItem->email = $email;
            $emailItem->rec_type = $status;
            $emailItem->save();
        }

        return ($emailItem->rec_type === EmailList::STATUS_BLACKLIST) ? false : true;
    }

    /**
     * Check whether email is verified or not.
     *
     * @param string $email
     *
     * @return bool
     */
    public function verified($email)
    {
        try {
            $cache_key = 'email_list_'.$this->company_id;

            $data = \Cache::get($cache_key);
            if (!empty($data)) {
                $email_list = \GuzzleHttp\json_decode(\Cache::get($cache_key), true);
                if (!empty($email_list[$email])) {
                    return ($email_list[$email] === EmailList::STATUS_BLACKLIST) ? false : true;
                }
            }
        } catch (\Exception $exception) {
        }

        try {
            $emailItem = EmailList::where([
                ['company_id', $this->company_id],
                ['email', $email]
            ])->firstOrFail();

            return ($emailItem->rec_type === EmailList::STATUS_BLACKLIST) ? false : true;
        } catch (\Exception $exception) {
        }

        return true;
    }

    /**
     * @param string $email
     */
    public function setToBlackList($email,$id)
    {
        $this->updateEmailListCache($email, EmailList::STATUS_BLACKLIST);
        $this->updateDBEmailList($id, EmailList::STATUS_BLACKLIST);
    }

    /**
     * @param string $email
     */
    public function setToWhiteList($email,$id)
    {
        $this->updateEmailListCache($email, EmailList::STATUS_WHITELIST);
        $this->updateDBEmailList($id, EmailList::STATUS_WHITELIST);
    }

    /**
     * @param string $email
     * @param string $status
     */
    protected function updateEmailListCache($email, $status)
    {
        try {
            $cache_key = 'email_list_'.$this->company_id;

            $data = \Cache::get($cache_key);
            if (!empty($data)) {
                $email_list = \GuzzleHttp\json_decode(\Cache::get($cache_key), true);
                $email_list[$email] = $status;
            } else {
                $email_list = [
                    $email => $status
                ];
            }

            CompanyAttributeData::removeEntry($cache_key);
            \Cache::forever($cache_key, \GuzzleHttp\json_encode($email_list));
        } catch (\Exception $exception) {
        }
    }

    /**
     * @param string $id
     * @param string $status
     */
    protected function updateDBEmailList($id, $status)
    {
        try {
            $emailItem = EmailList::find($id);

            $emailItem->rec_type = $status;
            $emailItem->save();
        } catch (\Exception $exception) {
        }
    }
}
