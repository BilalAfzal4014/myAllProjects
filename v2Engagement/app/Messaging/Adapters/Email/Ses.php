<?php
namespace App\Messaging\Adapters\Email;
use App\Messaging\Contracts\SendMessageContract;

class Ses implements SendMessageContract
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Compile API request data.
     */
    public function compileData($data)
    {
        $this->data = $data[0];
        if (empty($this->data['view']) && empty($this->data['body'])) {
            $this->data['view'] = 'emails.default';
        } else {
            $this->data['view'] = $this->data['message'];
        }

        if (empty($this->data['from'])) {
            $this->data['from']['address'] = config('mail.from.address');
            $this->data['from']['name'] = config('mail.from.name');
        }
    }

    public function send()
    {
        try {
            
            \Mail::send($this->data['view'], ['data' => $this->data], function ($message) {
                $message->from($this->data['from']['address'], $this->data['from']['name']);
                $message->to($this->data['email']);
                $message->subject($this->data['subject']);
            });

            return [
                'type' => 'success',
                'message' => 'Email sent successfully.'
            ];
        } catch (\Exception $exception) {
            return [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ];
        }
    }
}