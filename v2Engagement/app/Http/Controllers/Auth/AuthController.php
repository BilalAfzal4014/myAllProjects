<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-zA-Z]).{8,16}$/|confirmed',
            'CaptchaCode' => 'valid_captcha'
        ], [
            'password.regex' => 'Password must contain alphanumeric characters and having length between 8-16',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }


    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {

            if ($user->status == 0) {

                return redirect()->back()->withErrors(['Your account is inactive.']);
            }
            if ($user->is_deleted == 1) {

                return redirect()->back()->withErrors(['Your account is deleted.']);
            }
            if (Hash::check($request->password, $user->password)) {
//                $code = $request->input('CaptchaCode');
//                $isHuman = captcha_validate($code);
//                if ($isHuman) {
                // TODO: Captcha validation passed:
                // continue with form processing, knowing the submission was made by a human
                Auth::login($user);
                $user->last_login = \Carbon\Carbon::now();
                $user->save();


                $userRole = \Illuminate\Support\Facades\DB::select("SELECT * FROM user_has_roles where user_id = {$user->id}");
                if (!empty($userRole[0]->role_id) && $userRole[0]->role_id == 2) {
                    return redirect('/users');
                } else {
                    return redirect('/backend/attribute/attributeData');
                }


//                } else {
//                    // TODO: Captcha validation failed:
//                    // abort sensitive action, return an error message
//                    return redirect()->back()->withErrors(['Captcha validation failed']);
//                }
                // if ($user->status == 'inactive') {
                //     return redirect()->back()->with('errorMessage', trans('message.error_pending_approval'));
                // }

            } else {

                return redirect()->back()->withErrors(['Your credentials are not matched.']);
            }
        } else {

            return redirect()->back()->withErrors(['Your credentials are not matched.']);
        }
    }


}
