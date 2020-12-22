<?php

namespace App\Http\Controllers\Api;

use App\AppGroup;
use App\Components\AppStatusCodes;
use App\Components\ParseResponse;
use App\Console\Commands\RemoveExportUsersFilesCommand;
use App\Http\Controllers\Controller;
use App\Permissions;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\FilesystemAdapter;
use mysql_xdevapi\Exception;

class LoginController extends Controller
{
    use AuthenticatesUsers, ParseResponse;

    protected $clientRepository;


    public function __construct()
    {
        $this->middleware(['api.version', 'web']);

        $this->clientRepository = new ClientRepository();
    }

    /**
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|Response
     */
    public function signup(Request $request)
    {
        try {
            unset($request['companyid']);
            unset($request['confirmPassword']);
            $emailCheck = User::where('email', '=', $request->input('email'))->get();
            if (count($emailCheck) > 0) {
                return response()->json([
                    'status' => '400',
                    'message' => 'Email already added successfully'
                ]);
            } else {
                $usersList = User::create([
                    'company_key' => bin2hex(random_bytes(32)),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'logo' => $request->input('logo'),
                    'password' => bcrypt($request->input('password'))
                ]);
                Permissions::create([
                    'role_id' => '2',
                    'user_id' => $usersList->id,

                ]);
                $userRecord = User::where('id', '=', $usersList->id)->first();
                //dd($userRecord);
                \App\Components\CreateOAuthClients::createClients($userRecord);
                $this->createGroup($usersList);
                return response()->json([
                    'status' => '200',
                    'message' => 'company created successfully'
                ]);
            }

        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                $exception->getMessage(),
                'error'
            );
        }
    }

    private function createGroup($company)
    {
        AppGroup::create([
            'company_id' => $company->id,
            'name' => 'Default',
            'code' => str_random(16),
            'is_default' => 1,
            'created_by' => $company->id,
            'updated_by' => $company->id
        ]);
    }

//    public function companyListing(Request $request)
//    {
//
//        $queryChain = User::leftjoin('user_has_roles','user_has_roles.user_id','=','users.id')
//                      ->leftjoin('roles','roles.id','=','user_has_roles.role.id')
//                      ->where('users.deleted_at',NULL);
//        $totalCount = clone $queryChain;
//        $totalCount = $totalCount->count();
//        if ($request['sideFilters'] != null && $request['sideFilters'] != []) {
//            //   $queryChain->where('lookups'.'.'.$request['sideFilters']['parent'],'=', $request['sideFilters']['value']);
//        }
//
//        if ($request['query'] != null) {
//            $search = $request['query'];
//            $columns = $request['columns'];
//            $queryChain->where(function ($query) use ($search, $columns) {
//                $query->where('users.id', 'LIKE', "%{$search}%");
//                $query->orWhere('users.name', 'LIKE', "%{$search}%");
//                $query->orWhere('languages.code', 'LIKE', "%{$search}%");
//            });
//        }
//        $totalFiltered = clone $queryChain;
//        $totalFiltered = $totalFiltered->count();
//        isset($request["orderBy"]) ? $queryChain->orderBy($request["orderBy"], $request["ascending"] == 1 ? 'desc' : 'asc') : '';
//        $data = $queryChain->offset(($request['page'] - 1) * $request['limit'])
//            ->limit($request['limit'])
//            ->get(['users.*','roles.name as roleName']);
//        $meta = [
//            'pages' => ceil($totalFiltered / $request['limit']),
//            'page' => $request['page'],
//            'total' => $totalFiltered,
//        ];
//        return [
//            'meta' => $meta,
//            'data' => $data
//        ];
//
//
//    }

    public function store(Request $request)
    {
        $params = $this->parseResponse($request);

        $isCompany = in_array('company_key', array_keys($params)) ? true : false;

        return ($isCompany === true) ? $this->companyLogin($request) : $this->login($request);
    }

    /**
     * Handle a login request to the application through company key.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|Response
     */
    protected function companyLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'company_key' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                $validator->errors()->all(),
                'error'
            );
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        try {
            $user = User::where('company_key', $request->get('company_key'))->first();

            if ($user) {

                if ($user->is_active == '0') {
                    return $this->addResponse(
                        AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                        'error',
                        ['Company is not active'],
                        'error'
                    );
                    die;
                }
                if ($user->deleted_at != '') {
                    return $this->addResponse(
                        AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                        'error',
                        ['These credentials do not match our records.'],
                        'error'
                    );
                    die;
                }
                auth()->login($user);

                return $this->sendLoginResponse($request);
            } else {
                return $this->sendFailedLoginResponse($request);
            }

        } catch (\Exception $exception) {
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = \Lang::get('auth.throttle', ['seconds' => $seconds]);

        $errors = [$this->username() => $message];

        return $this->addResponse(AppStatusCodes::HTTP_LOCKED, 'error', $errors, 'error');
    }

    /**
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        //dd($user);
        try {

            if ($user->is_active == '0') {
                return $this->addResponse(
                    AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                    'error',
                    ['Company is not active'],
                    'error'
                );
                die;
            }
            if ($user->deleted_at != '') {
                return $this->addResponse(
                    AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                    'error',
                    ['These credentials do not match our records.'],
                    'error'
                );
                die;
            }

            $clients = $this->clientRepository->activeForUser($user->id);
            ///    dd($clients);
            if ($clients->count() > 0) {
                $clients = $clients->filter(function ($client) {
                    return ((bool)$client->revoked === false) && ($client->personal_access_client === true) ? $client : null;
                });
            }

            $client = $clients->first();

            Passport::personalAccessClient($client->id);

            $data['token'] = $user->createToken('AccessToken', ['*'])->accessToken;


            $user->api_token = $data['token'];
            $user->save();

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                'success',
                $data,
                'data'
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'erroraaa',
                [$exception->getMessage()],
                'error'
            );
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $field = !empty($request->get('company_key')) ? 'company_key' : $this->username();
        $errors = [trans('auth.failed')];

        return $this->addResponse(AppStatusCodes::HTTP_NOT_FOUND, 'error', $errors, 'error');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|Response
     */
    public function login(Request $request)
    {

        try {
            //dd($request->all());
            $validator = \Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
            // dd($validator);
            if ($validator->fails()) {
                return $this->addResponse(
                    AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                    'error',
                    $validator->errors()->all(),
                    'error'
                );
            }

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);
//
            return $this->sendFailedLoginResponse($request);
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                [$exception->getMessage()],
                'error'
            );
        }
    }

    public function language(Request $request)
    {

        $file = $request->file('file');
        //dd($file);
        $allowedMimeType = ["image/png", "image/jpeg", "image/gif", "image/psd", "image/bmp","image/x-ms-bmp"];
        /**
         * @var  $disk FilesystemAdapter
         */


        //dd($file->getClientMimeType());

        $disk = Storage::disk('s3');

        if (!in_array($file->getClientMimeType(), $allowedMimeType)) {

            return response()->json([
                'status' => 'type_err'
            ]);
        }

        $type = explode("/", $file->getMimeType());

        $companyDir = "app/assets";

        $temp = explode(".", $_FILES["file"]["name"]);

        $newfilename = substr($temp[0], 0, 20) . '.' . $type[1]; //end($temp);

        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\_\-\.]/', '', basename($newfilename));

        if (!$disk->exists($companyDir)) {
            $disk->makeDirectory($companyDir);
        }

        $disk->put($companyDir . '/' . $fileName, File::get($file), 'public');
        $imagePath = $disk->url($companyDir . '/' . $fileName);
        list($width, $height) = getimagesize($imagePath);
        $headers = get_headers($imagePath, true);
        return response()->json([
            'status' => '200',
            'message' => 'Image uploaded successfully',
            'imagePath' => $imagePath,
            'filename' => $fileName,
            'width' => $width,
            'height' => $height,
            'size' => $headers['Content-Length'] / 1024
        ]);
        // return $imagpath;
    }

    public function forgot(Request $request)
    {
        try {
            $allUser = $request->all();
            $userList = User::where('email', '=', $request->input('email'))->first();
            if (!$userList) {
                throw new \Exception('Email does not exist');
            }
            $password = self::randomPassword();
            User::where('email', '=', $request->input('email'))->update([
                'password' => bcrypt($password)
            ]);
            $data = array(
                'from' => config('mail.from.address'),
                'to' => $request->input('email'),
                'subject' => 'Forgot Password',
                'template_content' => $password,
            );
            Mail::send(['html' => 'emails.default'], $data,
                function ($message) use ($data) {
                    $message->to($data['to']);
                    $message->subject($data['subject']);
                    $message->from($data['from']);
                });
            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                'success',
                'Forgot password email has been send please check your email',
                'data'
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                $exception->getMessage(),
                'error'
            );
        }
    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
