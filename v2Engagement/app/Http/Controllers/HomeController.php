<?php

namespace App\Http\Controllers;

use App\EmailList;
use App\GathersServicesStats;
use App\Http\Middleware\visitorRoute;
use App\Libraries\VerifyEmail;
use App\UserAttribute;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use GathersServicesStats;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        if (auth()->user()) {
            if (auth()->user()->hasRole('COMPANY')) {
                $this->isCompany = true;
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function newDashboard()
    {
        $data = $this->gatherUserInfoStats();
        return view('dashboard.index', compact('data'));
    }

    public function index()
    {
        $companyid = Auth::user()->id;
        $data = $this->gatherUserInfoStats();
        if (Auth::user()->name == 'Super Admin') {
            $queryvalue = "(SELECT DISTINCT name FROM `app` WHERE is_active='1' AND is_deleted='0')";

        } else {
            $queryvalue = "(SELECT DISTINCT name FROM `app` WHERE is_active='1' AND company_id='$companyid' AND is_deleted='0')";

        }
        $userlist = DB::select(DB::raw($queryvalue));
        $latestUsers = UserAttribute::where('company_id', $companyid)->orderBy('last_login', 'desc')->limit(17)->get();
        //   dd($latestUsers);
        return view('dashboard.dashboardNew', compact('data', 'userlist', 'latestUsers'));
        //   return view('dashboard.index', compact('data'));
    }

    public function companyLogo($companyId)
    {

        $user = User::where('id', $companyId)->first();
        $logoName = "/html/images/ureka_logo2.png";
        if ($user->logo) {

            /**
             * @var $disk FilesystemAdapter
             */
            $disk = Storage::disk("s3");
            $logoName = $disk->url($user->logo);
        }
        return response()->json([
            'status' => true,
            'data' => $logoName,
            'message' => "Company Logo"
        ]);
    }

    public function emailListing()
    {
        $emailList = EmailList::all();
        $users = User::all();
        //dd($users);
        //dd(compact('emailList'));'
        return view('dashboard.emailListing')->with('users', $users);
    }

    public function emailListingFetch(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'username',
            2 => 'email',
            3 => 'rec_type',
            4 => 'created_at'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $companyId = $request->companyId;

        $myQuery = DB::table('email_list')->leftjoin('users', 'users.id', '=', 'email_list.company_id');
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where(function ($query) use ($search) {
                $query->orWhere('email_list.id', 'LIKE', "%{$search}%");
                $query->orWhere('email_list.email', 'LIKE', "%{$search}%");
                $query->orWhere('email_list.rec_type', 'LIKE', "%{$search}%");
                $query->orWhere('email_list.created_at', 'LIKE', "%{$search}%");
            });
        }
        switch ($filterType) {
            case 'app_name':
                if ($companyId > 0) {
                    $myQuery->where('email_list.rec_type', '=', $filter)->where('email_list.company_id', '=', $companyId);
                } else {
                    $myQuery->where('email_list.rec_type', '=', $filter);
                }
               // echo $myQuery->toSql();
                break;
            case 'company_name':
                $myQuery->where('email_list.company_id', '=', $filter);
                break;

        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get(['email_list.*', 'users.name as username']);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Email Listing'
        ]);
    }

    public function emailListingFilter($filter)
    {
        $arrayToReturn = array();
        $emailList = EmailList::where('rec_type', '=', $filter)->get();
        $arrayTemp = [];
        foreach ($emailList as $newFeedTemplates) {
            array_push($arrayTemp, [
                $newFeedTemplates->id,
                $newFeedTemplates->email,
                $newFeedTemplates->rec_type,
                $newFeedTemplates->created_at->format('F d, Y h:ia'),
                view('emails.emailTemplateAjax.actionCol', ['newFeedTemplates' => $newFeedTemplates])->render()

            ]);
        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));
    }

    public function emaildelete($id)
    {

        $emailList = EmailList::where('id', $id)->delete();
        if ($emailList) {
            return redirect('/emailListing')->with(['flash_message' => 'Blacklist Email Deleted']);
        } else {
            return redirect('/emailListing')->with(['flash_message' => 'Blacklist Email Deleted']);
        }
    }

    public function emailstatus($status, $id)
    {
        $emailList = EmailList::find($id);
        $operationString = '';
        $verifyEmailObj = new VerifyEmail($emailList->company_id);
        switch ($status) {
            case EmailList::STATUS_WHITELIST:
                {
                    $verifyEmailObj->setToBlackList($emailList->email, $id);
                    $operationString = 'Successfully marked blacklist';
                    break;
                }
            case EmailList::STATUS_BLACKLIST:
                {
                    $verifyEmailObj->setToWhiteList($emailList->email, $id);
                    $operationString = 'Successfully marked whitelist';
                    break;
                }
        }
        if ($emailList) {
            $response = array(
                'message' => $operationString
            );
            //   return redirect('/emailListing')->with(['flash_message' => $operationString]);
        } else {
            $response = array(
                'message' => $operationString
            );
            //    return redirect('/emailListing')->with(['flash_message' => $operationString]);
        }
        return response()->json($response);
    }
}
