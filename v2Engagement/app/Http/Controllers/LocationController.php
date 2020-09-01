<?php

namespace App\Http\Controllers;

use App\Api\Location\Location;
use App\AttributeData;
use App\Helpers\CommonHelper;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Api\Response\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;

class LocationController extends Controller
{

    protected $response;
    private $location;

    public function __construct(Location $location, Response $response)
    {
        $this->location = $location;
        $this->response = $response;
    }

    public function crud(Request $request, $id = null)
    {
        $companyId = Auth::user()->id;
        if ($id) {
            $locationObj = DB::table("location")->where('company_id', '=', $companyId)->where('id','=',$id)->first();
            if (!$locationObj) {
                abort(403, 'Unauthorized');
            }
        } else {
            $locationObj = null;
        }
        $currencies = json_decode(file_get_contents('json/countrieslist.json'), true);
        $availRoleArr = Auth::user()->roles()->pluck('name')->toArray();
        $users = CommonHelper::getActiveCompanies();
        return view('location.crud', array("currencies" => $currencies['CcyNtry'], "locationObj" => $locationObj, "users" => $users, "availRoleArr" => $availRoleArr));
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $authUser = Auth::user()->id;
        $companyId = $request->input('company_id');
        if ($companyId != $authUser) {
            abort(403, 'Unauthorized');
        }
        $this->location->store_location($data);
        return Redirect::route('location.index');
    }

    public function index(Request $request)
    {

        return view('location.locationListing');
    }

    public function fetch(Request $request)
    {

        list($totalData, $totalFiltered, $campaignListing) = $this->location->locationListing($request);
        return response()->json([
            'status' => true,
            'data' => $campaignListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign listing'
        ]);
    }

    public function delete_location(Request $request)
    {
        $companyId = Auth::user()->id;
        $id = $request->id;
        $result = DB::table('location')
            ->where('company_id', '=', $companyId)
            ->where('id', $id)->update(['is_deleted' => 1]);
        if ($result) {
            return $this->response->success();
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function checkDuplication(Request $request)
    {
        $defaultName = $request->get('name');
        $id = \Illuminate\Support\Facades\Auth::user()->id;

        $checkDup = DB::table('location')
            ->where('company_id', $id)->where('default_name', $defaultName)->first();

        if ($checkDup) {

            return new JsonResponse(array(
                'status' => 500,
                "message" => "Location with same name already exist"
            ));
        } else {

            return new JsonResponse(array(
                'status' => 200,
                "message" => ""
            ));
        }
    }
}
