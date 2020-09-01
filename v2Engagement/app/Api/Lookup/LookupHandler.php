<?php
/**
 * Created by PhpStorm.
 * User: Hassan Raza
 * Date: 12/10/2018
 * Time: 10:42 AM
 */

namespace App\Api\Lookup;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Api\Helpers\Helper;
use \Config;
use Illuminate\Support\Facades\Redirect;

class LookupHandler
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
        $lookups = DB::table('lookup')->groupBy('id')->get();
        $lookups = $this->helper->convert_object_to_array($lookups);
        return $lookups;
    }

    public function delete_lookup($id)
    {
        DB::table('lookup')
            ->where('id', '=', $id)
            ->delete();
        return true;
    }

    public function store_lookup($data)
    {
        try {
            DB::table('lookup')->insert($data);
            return ["error" => false, "msg" => ""];
        } catch (\Exception $ex) {

            return ["error" => true, "msg" => $ex->getMessage()];
        }
    }

    public function get_all_lookup_query($parent = null)
    {

        $myQuery = DB::table('lookup');
        if ($parent) {

            $myQuery->where('lookup.parent_id', 0);
        }
        return $myQuery->get();
    }

    public function lookupListing($request, $companyId)
    {
        if (Auth::user()->name == "Super Admin") {
            $companyId = '1';
            $status = 'PLATFORM';
        } else {
            $status = 'COMPANY';
        }
        $search = $request->input('search.value');
        $filter = $request->filter;  // ""
        $resultSet = DB::table('lookup')
            ->leftjoin('lookup as l2', 'l2.id', '=', 'lookup.parent_id')
            ->groupBy('id')
            ->select("l2.code as parent", "lookup.id", "lookup.code", "lookup.name", "lookup.created_date as created_by")
            ->where('lookup.company_id', '=', $companyId)
            ->where('lookup.level', '=', $status)
            ->orderBy("id", "desc")
            ->get();
        $totalResult = count($resultSet);
        if ($filter || $search) {
            $myQuery = DB::table('lookup')
                ->leftjoin('lookup as l2', 'l2.id', '=', 'lookup.parent_id');
            if ($search) {

                $myQuery->where(function ($query) use ($search) {
                    $query->where('lookup.code', 'LIKE', "%{$search}%")
                        ->orWhere('lookup.name', 'LIKE', "%{$search}%");
                });
            }
            if ($filter) {
                $myQuery->where('lookup.parent_id', $filter);
            }
            $resultSet = $myQuery->select("l2.code as parent", "lookup.id", "lookup.code", "lookup.name", "lookup.description", "lookup.created_date as created_by")
                ->where('lookup.company_id', '=', $companyId)
                ->where('lookup.level', '=', $status)
                ->orderBy("id", "desc")->get();
        }
        return array($totalResult, count($resultSet), $resultSet);

    }

    public function edit_lookup($id)
    {
        $single_lookup = DB::table('lookup')
            ->where('id', '=', $id)
            ->get();
        // For getting the big query simple sql query.
        //$sql = $single_lookup->toSql();
        //$bindDataArr = $single_lookup->getBindings();
        //$resp = $this->helper->getSqlWithBinding($sql,$bindDataArr);
        $single_lookup = $this->helper->convert_object_to_array($single_lookup);
        return $single_lookup;
    }

    public function update_lookup($data, $id)
    {
        try {
            unset($data['code']);
            DB::table('lookup')
                ->where('id', $id)
                ->update($data);
            return ["error" => false, "msg" => ""];
        } catch (\Exception $ex) {

            return ["error" => true, "msg" => $ex->getMessage()];
        }
    }
}