<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Settings;
use Auth;
//Enables us to output flash messaging
use Session;

use App\Engagment\Setting\Setting;

class SettingController extends Controller {

    protected $settings;

    public function __construct(Settings $settings) {
        $this->settings = $settings;
        // $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
        // echo Carbon::now();exit;
        //Get all users and pass it to the view
        $settings = Settings::where(['is_deleted' => 0])->get();
        return view('setting.index')->with('settings', $settings);
    }
   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {

        //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        $inputData = $request->only('email', 'name', 'password');
        $inputData['updated_by'] = 1;
        $inputData['created_by'] = 1;
        $user = User::create( $inputData ); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
        if (isset($roles)) {//Checking if a role was selected
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }
        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
                         ->with('flash_message', 'User successfully added.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('users');
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
         $setting = Settings::findOrFail($id); //Get user with specified id
         $decodeddata = $this->object_to_array(json_decode($setting['setting_data']));
         $decodeddata['id'] = $setting['id'];
         return view('setting.edit')->with('setting', $decodeddata);; //pass user and roles data to view

    }

    public function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request) {

        $updateid = $request->input('id');
        $data = $request->input();
        unset($data['_token']);
        unset($data['id']);
        $data = json_encode($data);
        $resp = $this->settings->updateSettingsByID($updateid, $data);
 
         if ($resp) {
            Session::flash('message', "Settings updated successfully");
        return redirect()->route('setting')
            ->with('flash_message', 'User successfully edited.');
           
          }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy( $id ) {

        //Find a user with a given id and delete
        // $user = User::findOrFail($id);
        // $user->delete();
        $user = User::findOrFail( $id ); //Get role specified by id
        $user->is_deleted = 1;
        $user->save();

        return redirect()->route('users.index')
                         ->with('flash_message', 'User successfully deleted.');
    }
}
