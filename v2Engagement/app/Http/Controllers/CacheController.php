<?php

namespace App\Http\Controllers;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public function index(Request $request)
    {
        $rows = [];
        $company_id = $request->get('company_id');

        $users = Role::findByName('COMPANY')->users;

        if (!empty($company_id)) {
            $cache_rows = array_keys(
                CompanyAttributeData::rows($company_id)
            );
            $attributes = AttributeData::selectRaw('DISTINCT(row_id)')->where('company_id', $company_id)->where('data_type','=','user');
            if ($attributes->count() > 0) {
                $rows = $attributes->get()->toArray();
                foreach ($rows as $key => $row) {
                    if (!in_array($row['row_id'], $cache_rows)) {
                        $rows[$key]['sync'] = false;
                    } else {
                        unset($rows[$key]);
                    }
                }

                $perPage = 20;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $data = collect($rows);
                $currentPageItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
                $rows= new LengthAwarePaginator($currentPageItems , count($data), $perPage);
                $rows->setPath('/company/cache?company_id='.$company_id);
            }
        }

        return view('cache.index', [
            'rows'          => $rows,
            'users'         => $users
            
        ]);
    }   

    function ExecuteCommand(Request $request){
        
        $command  = $request->input('command');
        
        $cache_key = $command;
        $data = \Cache::get($cache_key);

        return !empty($data) ? \GuzzleHttp\json_decode($data, true) : [];
    }
}
