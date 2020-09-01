<?php

namespace App\Http\Controllers;

use App\AttributeData;
use App\Campaign;
use App\Components\CompanyAttributeData;
use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Helpers\CommonHelper;
use App\UserAttribute;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
//Enables us to output flash messaging
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{

    public $attributeDataWrapper;

    public function __construct(AttributeDataWrapper $attributeDataWrapper)
    {
        $this->attributeDataWrapper = $attributeDataWrapper;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userArray = array();
        //dd(strpos($requ;lest->route()->getAction()['controller'],'UserController'));
//        $rolesArr = Auth::user()->roles()->pluck('name')->toArray();
        //dd($rolesArr);
        // echo Carbon::now();exit;
        //Get all users and pass it to the view
        $users = User::where([
            ['is_deleted', '=', 0],
            ['id', '!=', \Auth::user()->id]
        ])->get();
        if (count($users) > 0) {

            for ($val = 0; $val < count($users); $val++) {
                $role = $users[$val]->roles()->pluck('name')->implode(' ');
                $companyUsersCount = UserAttribute::where('company_id', $users[$val]['id'])->count();
                $userArray[] = array(
                    'id' => $users[$val]['id'],
                    'name' => $users[$val]['name'],
                    'email' => $users[$val]['email'],
                    'created_at' => $users[$val]['created_at'],
                    'status' => $users[$val]['status'],
                    'roles' => $role,
                    'companyUserCount' => $companyUsersCount
                );
            }
        } else {
            $userArray = [];
        }
        //dd($userArray);
        return view('users.index')->with(['userArray' => $userArray]);
    }

    public function deleteEntries(Request $request)
    {


        $companyId = $request->userId;
        $status = $request->status;
        $getAllAttributes = $this->attributeDataWrapper->getListingForDataTable($companyId);
        $flashMessage = "";
        switch ($status) {
            case 0:

                $arr = [];
                foreach ($getAllAttributes as $allAttribute) {

                    if (isset($allAttribute->app_name) && $allAttribute->user_id) {
                        $cahcheKey = CommonHelper::generateCacheKey($companyId, $allAttribute->app_name, $allAttribute->user_id);

                        Cache::forget($cahcheKey);
                        CompanyAttributeData::removeEntries($companyId, $allAttribute->row_id);
                        array_push($arr, $allAttribute->row_id);
                    }
                }
                AttributeData::where("company_id", $companyId)->delete();

                /**
                 * @var $disk FilesystemAdapter
                 */
                $disk = Storage::disk('importJson');
                $disk->deleteDirectory('company_' . $companyId);


                $campaigns = Campaign::all();
                $campaignIds = $campaigns->pluck('id')->unique()->toArray();

                foreach ($campaignIds as $campaignId) {
                    $cache_key = "company_{$companyId}_campaign_{$campaignId}_tracking";

                    \Cache::forget($cache_key);

                }
                $flashMessage = 'Attribute removed from cache and database';

                break;
            case 1:

                $arr = [];
                foreach ($getAllAttributes as $allAttribute) {

                    if (isset($allAttribute->app_name) && $allAttribute->user_id) {
                        $cahcheKey = CommonHelper::generateCacheKey($companyId, $allAttribute->app_name, $allAttribute->user_id);

                        Cache::forget($cahcheKey);
                        CompanyAttributeData::removeEntries($companyId, $allAttribute->row_id);
                        array_push($arr, $allAttribute->row_id);
                    }
                }

                /**
                 * @var $disk FilesystemAdapter
                 */
                $disk = Storage::disk('importJson');
                $disk->deleteDirectory('company_' . $companyId);


                $campaigns = Campaign::all();
                $campaignIds = $campaigns->pluck('id')->unique()->toArray();

                foreach ($campaignIds as $campaignId) {
                    $cache_key = "company_{$companyId}_campaign_{$campaignId}_tracking";

                    \Cache::forget($cache_key);

                }
                $flashMessage = 'Attribute removed from cache';

                break;
            case 2:

                AttributeData::where("company_id", $companyId)->delete();

                /**
                 * @var $disk FilesystemAdapter
                 */
                $disk = Storage::disk('importJson');
                $disk->deleteDirectory('company_' . $companyId);

                $flashMessage = 'Attribute removed from database';
                break;
        }
        return redirect()->route('users.index')
            ->with('flash_message', $flashMessage);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::get();
        return view('users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $disk = Storage::disk('seeders');
        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $inputData = $request->only('email', 'name', 'password');
        $password = $inputData['password'];
        $inputData['company_key'] = bin2hex(random_bytes(32));
        $inputData['updated_by'] = 1;
        $inputData['created_by'] = 1;
        $inputData['password'] = bcrypt($inputData['password']);
        $user = User::create($inputData); //Retrieving only the email and password data
        $isCompany = false;
        $roles = $request['roles']; //Retrieving the roles field
        if (isset($roles)) {//Checking if a role was selected
            foreach ($roles as $role) {
                if ($role == 3) {
                    $isCompany = true;
                }

                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }

        if ($isCompany) {
            /*            $query = \Storage::disk('local')->get('company_patch.stub');
                        $query = str_replace('COMPANY_ID', $user->id, $query);
                        $query = str_replace('CREATED_DATE', Carbon::now()->toDateTimeString(), $query);

                        \DB::statement($query);*/
        }

        $name = $inputData['name'];
        $email = $inputData['email'];
        $data = array(
            'name' => $name,
            'email' => $email,
            'password' => $password
        );

        $message = "";
        try {
            Mail::send(['html' => 'email'], $data, function ($message) use ($name, $email) {
                //$m->from('muhammad.usman@rebeltechnology.io', 'Your Application');
                $message->to($email, $name)->subject('Engagement:Company account created');
            });
        } catch (\Exception $exception) {

            $message = $exception->getMessage();
        }

        $companyId = $user->id;
        $milliseconds = round(microtime(true) * 1000);
        $maxRawId = $milliseconds * $companyId;
        $defaultConversionAction = $disk->get('conversions_action.stub');
        $defaultConversionAction = str_replace('COMPANY_ID', $companyId, $defaultConversionAction);
        $defaultConversionAction = str_replace('ROW_ID', $maxRawId, $defaultConversionAction);
        DB::statement($defaultConversionAction);
        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message', 'User successfully added. ' . $message);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles
        $availRoleArr = Auth::user()->roles()->pluck('name')->toArray();
        return view('users.edit', compact('user', 'roles', 'availRoleArr')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,' . $id,
            // 'password'=>'required|min:6|confirmed'
        ]);

        $user = User::findOrFail($id); //Get role specified by id
        $user->updated_by = 2;
        $inputData = $request->only(['name', 'email']); //Retreive the name, email and password fields
        $password = $request->get('password');
        if ($password) {
            $inputData['password'] = bcrypt($password);
        }
        $user->fill($inputData)->save();

        $roles = $request['roles']; //Retreive all roles
        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        } else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message', 'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //Find a user with a given id and delete
        // $user = User::findOrFail($id);
        // $user->delete();
        $user = User::findOrFail($id); //Get role specified by id
        $user->is_deleted = 1;
        $user->save();

        return redirect()->route('users.index')
            ->with('flash_message', 'User successfully deleted.');
    }

    public function updateUser(Request $request)
    {
        $companyid = Auth::user()->id;
        $availRoleArr = Auth::user()->roles()->pluck('name')->toArray();
        if ($companyid != $request->input('companyKeyOrg') && !in_array('SUPER-ADMIN', $availRoleArr)) {
            abort(403, 'Unauthorized');
        }
        switch ($request->input('tab')) {
            case 'tab1':
                $email = User::where('email', $request->input('companyEmail'))
                    ->where('id', '<>', $request->input('companyKeyOrg'))
                    ->first();

                if ($email) {
                    return response()->json([
                        'status' => false,
                        'data' => [],
                        'message' => 'Email Already Exist'
                    ]);
                }
                $logo = '';
                $user = User::find($request->input('companyKeyOrg'));
                $user->name = $request->input('companyName');

                $user->phone = $request->input('companyPhone');

                $availRoleArr = Auth::user()->roles()->pluck('name')->toArray();
                if (in_array('SUPER-ADMIN', $availRoleArr) && \Auth::user()->id != $user->id) {


                    $user->email = $request->input('companyEmail');
                    $user->status = $request->input('status');
                }

                if ($request->hasFile('companyLogo')) {

                    /**
                     * @var $disk FilesystemAdapter
                     */
                    $disk = Storage::disk('s3');
                    $companyDir = "company_" . auth()->user()->id;
                    if (!$disk->exists($companyDir)) {
                        $disk->makeDirectory($companyDir);
                    }
                    if ($user->logo and $disk->exists($user->logo)) {

                        $disk->delete($user->logo);
                    }
                    $file = $request->file('companyLogo');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $request->input('companyKeyOrg') . '-' . time() . '.' . $extension;
                    $fileName = $companyDir . '/logo/' . $fileName;
                    $disk->put($fileName, File::get($file), 'public');
                    $user->logo = $fileName;
                    if ($request->input('companyKeyOrg') == Auth::user()->id)
                        $logo = url('storage/uploads/logo/') . '/' . $user->logo;

                }
                $user->save();
                return response()->json([
                    'status' => true,
                    'data' => $logo,
                    'message' => 'Information updated successfully'
                ]);
                break;
            case 'tab2':
                $user = User::find($request->input('companyKeyOrg'));
                $user->ios_passphrase = $request->input('Passphrase');
                if ($request->hasFile('companyFile1')) {
                    $file = $request->file('companyFile1');
//                    dump($file);die;
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $user->name . '-production-' . time() . '-' . $file->getClientOriginalName();
                    Storage::disk('certificates')->put($fileName, File::get($file));
                    $user->ios_cert_live = $fileName;
                }

                if ($request->hasFile('companyFile2')) {
                    $file = $request->file('companyFile2');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $user->name . '-development-' . time() . '-' . $file->getClientOriginalName();
                    Storage::disk('certificates')->put($fileName, File::get($file));
                    $user->ios_cert_dev = $fileName;
                }
                $user->firebase_server_api_key = $request->input('fireBaseKey');
                $user->save();
                return response()->json([
                    'status' => true,
                    'data' => '',
                    'message' => 'Information updated successfully'
                ]);
                break;
            case 'tab3':
                $user = User::find($request->input('companyKeyOrg'));
                $user->smtp_host = $request->input('smtpHost');
                $user->smtp_user = $request->input('smtpUser');
                $user->smtp_password = $request->input('smtpPassword');
                $user->smtp_port = $request->input('smtpPort');
                $user->ssl_tsl = $request->input('typeRadio');
                $user->smtp_from_name = $request->input('smtpFromName');
                $user->smtp_from_email = $request->input('smtpFromEmail');
                $user->save();
                return response()->json([
                    'status' => true,
                    'data' => '',
                    'message' => 'Information updated successfully'
                ]);
                break;
            case 'tab4':
                $user = User::find($request->input('companyKeyOrg'));
                $user->password = bcrypt($request->input('password'));
                $user->save();
                return response()->json([
                    'status' => true,
                    'data' => '',
                    'message' => 'Password updated successfully'
                ]);
        }


    }


    public function Status(Request $request)
    {

        $userId = $request->userId;
        $status = $request->status;
        $userObj = User::find($userId);
        $userObj->status = ($status == 1) ? 0 : 1;
        $userObj->save();
        return redirect()->route('users.index')->with(['flash_message' => 'User Status Updated']);
    }

    public function userListing(Request $request)
    {

        if ($request->operation == "active") {
            $users = User::where("status", 1)->get();
            $status = 'Active';
        } else {

            $users = User::where("status", 0)->get();
            $status = 'In Active';
        }

        $arrayTemp = [];
        foreach ($users as $user) {
            $usersCount = UserAttribute::where('company_id', $user->id)->count();
            array_push($arrayTemp, [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('F d, Y h:ia'),
                $user->roles()->pluck('name')->implode(' '),
                $status,
                $usersCount,
                view('users.userAjax.actionCol', ["user" => $user])->render()

            ]);
        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));
    }
}
