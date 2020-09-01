<?php

namespace App\Console\Commands;

use App\AttributeData;
use App\EmailList;
use App\Libraries\VerifyEmail;
use App\User;
use App\UserAttribute;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class FlagEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flag:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to flag emails available in attribute_data as blacklist/whitelist.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $verify_email = config('engagement.message.verify_email');
        if (isset($verify_email) && ((bool)$verify_email === false)) {
            $this->error("Email verification has been disabled!");
            return;
        }

        $companies = [];
        $id = $this->option('company');
        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                $store = EmailList::select(\DB::raw('DISTINCT(email)'))->where('company_id', $user->id);
                if ($store->count() > 0) {
                    $store = $store->get()->pluck('email');
                } else {
                    $store = collect();
                }

                $data = UserAttribute::select('email')->where([
                    ['company_id', $user->id]
                ]);

                if ($data->count() > 0) {
                    $emails = $data->get()->pluck('email')->unique()->toArray();

                    if ($store->count() > 0) {
                        $currentEmails = $store->toArray();
                        $emails = collect($emails)->filter(function ($item) use($currentEmails) {
                            return !in_array($item, $currentEmails) ? $item : null;
                        })->toArray();
                    } else {
                        $emails = $store->merge($emails)->toArray();
                    }

                    if (!empty($emails)) {
                        foreach ($emails as $email) {
                            $this->comment("Verifying Email {$email}");
                            $status = (new VerifyEmail($user->id))->verify($email);
                            if ($status === false) {
                                $this->error("Invalid email {$email}");
                            } else {
                                $this->info("Verified Email {$email}");
                            }
                        }

                        $store = EmailList::where('company_id', $user->id);
                        if ($store->count() > 0) {
                            $store = $store->get()->map(function ($email) {
                                return [$email->email => $email->rec_type];
                            })->flatten(1)->toArray();

                            \Cache::forget('email_list_'.$user->id);
                            \Cache::forever('email_list_'.$user->id, \GuzzleHttp\json_encode($store));
                        }
                    }
                }
            }
        }
    }

    /**
     * Configure input parameters.
     */
    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Company ID');
    }
}
