<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Messaging\Message;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Validator;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    protected $redirectTo = '/dashboard';
    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        $user = \Password::broker($broker)->getUser(
            $this->getSendResetLinkEmailCredentials($request)
        );

        if (is_null($user)) {
            $code = \Password::INVALID_USER;
            return $this->getSendResetLinkEmailFailureResponse($code);
        }

        $config = config('auth.passwords.users');
        $connection = isset($config['connection']) ? $config['connection'] : null;

        $token = (new DatabaseTokenRepository(
            app('db')->connection($connection),
            $config['table'],
            config('app.key'),
            $config['expire']
        ))->create($user);

        $response = $this->emailResetLink($user, $token, $config['email']);
        if ($response['type'] == 'success') {
            $code = \Password::RESET_LINK_SENT;
        }

        switch ($code) {
            case \Password::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse($code);
            case \Password::INVALID_USER:
            default:
                return $this->getSendResetLinkEmailFailureResponse($code);
        }
    }

    /**
     * Send the password reset link via e-mail.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $token
     * @param  string $view
     *
     * @return array|string
     * @throws \Throwable
     */
    protected function emailResetLink($user, $token, $view)
    {
        $body = view($view, compact('token', 'user'))->render();

        $email = new Message();
        $email->setAdapter('email', 'ses');

        $data = [
            'email' => $user->getEmailForPasswordReset(),
            'from' => config('mail.from'),
            'subject' => $this->getEmailSubject(),
            'message' => $body
        ];

        $email->compileData($data);

        return $email->send();
    }

    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);
            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }

    public function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-zA-Z]).{8,16}$/|confirmed',
        ];
    }

    public function getResetValidationMessages()
    {
        return [
            'password.regex' => 'Password must contain alphanumeric characters and having length between 8-16',
        ];
    }
}
