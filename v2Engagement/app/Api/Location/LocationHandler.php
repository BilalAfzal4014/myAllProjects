<?php
/**
 * Created by PhpStorm.
 * User: Hassan Raza
 * Date: 12/10/2018
 * Time: 10:42 AM
 */

namespace App\Api\Location;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Api\Helpers\Helper;
use \Config;
use Illuminate\Support\Facades\Redirect;

class LocationHandler
{
    protected $per_page;
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->per_page = 100;
        $this->helper = $helper;
    }
    /*
     * Get all lookups data
     * get_all_compaign() will return an array
    */
    public function get_all_lookup()
    {
        $lookups=DB::table('lookup')->groupBy('id')->get();
        $lookups = $this->helper->convert_object_to_array($lookups);
        return $lookups;
    }
    public function delete_lookup($id){
        DB::table('lookup')
            ->where('id','=',$id)
            ->delete();
        return true;
    }
    public function store_location($data){
        try
        {
            DB::table('location')->insert($data);
            return ["error" => false,"msg"=>""];
        }
        catch(\Exception $ex)
        {

            return ["error" => false,"msg"=>$ex->getMessage()];
        }
    }

    public function get_all_lookup_query($parent = null){

        $myQuery = DB::table('lookup');
        if ($parent) {

            $myQuery->where('lookup.parent_id', 0);
        }
        return $myQuery->get();
    }
    public function locationListing(Request $request){

        $availRoleArr = Auth::user()->roles()->pluck('name')->toArray();

        $column = [
            "name",
            "lat",
            "lng",
            "radius",
            "currency",
            "created_at",
        ];

        $orderColumn = $column[$request->input('order.0.column')];
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $filter = $request->filter;  // ""
        $filterType = $request->filterType;  // ""
        $resultSet = DB::table('location')
            ->select("location.address","location.id","location.default_name as name","location.lat", "location.lng","location.radius","location.currency", "location.created_at as created_at")
            ->where("location.is_deleted",0)
            ->orderBy($orderColumn,$dir)
            ->offset($start)
            ->limit($limit);
        if(!in_array('SUPER-ADMIN',$availRoleArr)){
            $resultSet = $resultSet->where("company_id",Auth::user()->id);
        }
        $totalResult = count($resultSet->get());
        if($search) {
            $myQuery = DB::table('location');
            if ($search) {

                $myQuery->where(function ($query) use ($search) {
                    $query->where('location.default_name', 'LIKE', "%{$search}%")
                        ->orWhere('location.lat', 'LIKE', "%{$search}%")
                        ->orWhere('location.lng', 'LIKE', "%{$search}%");
                });
            }
            $resultSet = $myQuery
                ->select("location.id","location.default_name as name","location.lat", "location.lng","location.currency", "location.created_at as created_at")
                ->where("location.is_deleted",0)
                ->orderBy($orderColumn,$dir)
                ->offset($start)
                ->limit($limit);

        }

        if($filterType == "platform"){
            $resultSet
                ->where($filterType,$filter);
        }
        $resultSet = $resultSet->get();
        return array($totalResult, count($resultSet), $resultSet);

    }
    public function edit_lookup($id){
        $single_lookup = DB::table('lookup')
            ->where('id','=',$id)
            ->get();
        // For getting the big query simple sql query.
        //$sql = $single_lookup->toSql();
        //$bindDataArr = $single_lookup->getBindings();
        //$resp = $this->helper->getSqlWithBinding($sql,$bindDataArr);
        $single_lookup = $this->helper->convert_object_to_array($single_lookup);
        return $single_lookup;
    }
    public function update_lookup($data, $id){
        try
        {
            DB::table('location')
                ->where('id', $id)
                ->update($data);
            return ["error" => false,"msg"=>""];
        }catch(\Exception $ex)
        {

            return ["error" => true,"msg"=>$ex->getMessage()];
        }
    }
}