<?php
/**
 * @resource Example
 *
 * Longer description
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Api\Lookup\Lookup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\JsonResponse;

class LookupController extends Controller
{
    protected $lookup;
    protected $request;

    /*
     * Initializing protected variables in constructor
     */
    public function __construct(Lookup $lookup, Request $request)
    {
        $this->lookup = $lookup;
        $this->request = $request;
    }

    /**
     * Display all Compaigns.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_all(Request $request)
    {

        $companyId = Auth::user()->id;
        return view('lookup.lookupListing', ['companyId' => $companyId, "error" => $request->error]);
    }

//    public function fetch_lookup(Request $request)
//    {
//        $companyId = Auth::user()->id;
//        list($totalData, $totalFiltered, $campaignListing) = $this->lookup->lookupListing($request, $companyId);
//        return response()->json([
//            'status' => true,
//            'data' => $campaignListing,
//            "draw" => intval($request->input('draw')),
//            "recordsTotal" => intval($totalData),
//            "recordsFiltered" => intval($totalFiltered),
//            'message' => 'Campaign listing'
//        ]);
//    }

    /****testing function *****/
    public function fetch_lookup(Request $request)
    {
        $companyId = Auth::user()->id;
        $CompanyName = Auth::user()->name;
//        dd($CompanyName);
        $columns = array(
            0 => 'code',
            1 => 'name',
            2 => 'l2.code',
            3 => 'lookup.created_date'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
//        dd($filterType);
        if ($CompanyName == "Super Admin") {
            $myQuery = \App\Lookup::leftjoin('lookup as l2', 'l2.id', '=', 'lookup.parent_id');
        } else {
            $myQuery = \App\Lookup::leftjoin('lookup as l2', 'l2.id', '=', 'lookup.parent_id')
                ->where('lookup.company_id', '=', $companyId)
                ->where('lookup.level', '=', 'COMPANY');
        }

        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery = $myQuery->where(function ($query) use ($search) {

                $query->orWhere('lookup.code', 'LIKE', "%{$search}%");
                $query->orWhere('lookup.name', 'LIKE', "%{$search}%");

            });
        }
        switch ($filterType) {
            case 'app-name':
                $myQuery->where('lookup.parent_id', $filter);
                break;
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get([
                "l2.code as parent",
                "lookup.id as id",
                "lookup.code as code",
                "lookup.name as name",
                "lookup.created_date as created_by"
            ]);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Lookup Data Found'
        ]);
    }


    public function create(Request $request)
    {

        $lookupParentListing = $this->lookup->get_all_lookup_listing('parent');
        $id = $request->id;
        $lookUpObj = null;
        if ($id) {
            $lookUpObj = DB::table('lookup')->where("id", $id)->first();
        }
        //  dd($lookUpObj);

        return view('lookup.create', array(
            "parentListing" => $lookupParentListing,
            "lookUpObj" => $lookUpObj,

        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete_lookup($id)
    {
        return $this->lookup->delete_lookup($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_lookup(Request $request)
    {
        $data = $request->all();
        return $this->lookup->store_lookup($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (Auth::user()->name == "Super Admin") {
            $data['level'] = 'PLATFORM';
            $data['company_id'] = Auth::user()->id;

        } else {
            $data['level'] = 'COMPANY';
            $data['company_id'] = Auth::user()->id;
        }
        $response = $this->lookup->store_lookup($data);

        $response = json_decode($response->getContent());

        if ($response->status_code == 200) {

            return Redirect::route('lookup.index');
        } else {

            return Redirect::route('lookup.index', ["error" => $response->error->msg]);
        }
    }

    public function checkDuolication(Request $request)
    {

        $code = strtoupper(str_replace(' ', '_', $request->code));
        $id = $request->id;
        if ($id) {

            $lookUpObj = \App\Lookup::where("id", $id)->where("code", $code)->where("company_id",Auth::user()->id)->first();
            if ($lookUpObj) {
                return new JsonResponse(array(
                    "error" => false,
                    "msg" => "error"
                ));
            }

        }

        $lookUpObj = \App\Lookup::where("code", $code)->where("company_id",Auth::user()->id)->first();


        if ($lookUpObj) {
            return new JsonResponse(array(
                "error" => true,
                "msg" => "Already Exist in database"
            ));
        } else {
            return new JsonResponse(array(
                "error" => false,
                "msg" => ""
            ));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit_lookup($id)
    {
        return $this->lookup->edit_lookup($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update_lookup(Request $request, $id)
    {
        $data = $request->all();
        return $this->lookup->update_lookup($data, $id);
    }
}
