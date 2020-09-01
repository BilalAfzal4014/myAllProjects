<?php
/**
 * Created by PhpStorm.
 * User: Hassan Raza
 * Date: 12/10/2018
 * Time: 10:42 AM
 */

namespace App\Api\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Api\Helpers\Helper;
use \Config;
use Illuminate\Support\Facades\Redirect;

class AppHandler
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
    public function store_lookup($data){
        try
        {
            DB::table('lookup')->insert($data);
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
    public function appListing(Request $request, $companyId){

        $companyId = Auth::user()->id;
        $column = [
            "name",
            "app_id",
            "description",
            "platform",
            "created_at",
        ];

        $orderColumn = $column[$request->input('order.0.column')];
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $filter = $request->filter;  // ""
        $filterType = $request->filterType;  // ""
        $resultSet = DB::table('app')
            ->select("app.id","app.name","app.app_id", "app.description", "app.platform", "app.created_at as created_at")
            ->where("app.company_id",$companyId)
            ->where("app.is_deleted",0)
            ->orderBy($orderColumn,$dir)
            ->offset($start)
            ->limit($limit);

        $totalResult = count($resultSet->get());
        if($search) {
            $myQuery = DB::table('app');
            if ($search) {

                $myQuery->where(function ($query) use ($search) {
                    $query->where('app.name', 'LIKE', "%{$search}%")
                        ->orWhere('app.app_id', 'LIKE', "%{$search}%")
                        ->orWhere('app.description', 'LIKE', "%{$search}%")
                        ->orWhere('app.platform', 'LIKE', "%{$search}%");
                });
            }
            $resultSet = $myQuery
                ->select("app.id","app.name","app.app_id", "app.description", "app.platform", "app.created_at as created_at")
                ->where("app.company_id",$companyId)
                ->where("app.is_deleted",0)
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
            DB::table('lookup')
                ->where('id', $id)
                ->update($data);
            return ["error" => false,"msg"=>""];
        }catch(\Exception $ex)
        {

            return ["error" => true,"msg"=>$ex->getMessage()];
        }
    }
}