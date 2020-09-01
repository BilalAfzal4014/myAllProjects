<?php

namespace App\Http\Controllers;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyCacheController extends Controller
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

        return view('campaign.tracking.index', [
            'rows'          => $rows,
            'users'         => $users,
            'company_id'    => $company_id
        ]);
    }

    public function store(Request $request)
    {
        $response = [];
        try {
            $company_id = $request->get('company_id');
            $row_id = $request->get('row_id');

            CompanyAttributeData::updateRow($company_id, $row_id);

            CompanyAttributeData::syncCompanyCache($row_id,$company_id);
            $rows = CompanyAttributeData::rows($company_id);
            if (!in_array($row_id, array_keys($rows))) {
                throw new \Exception();
            }
            $response = [
                'type' => 'success',
                'message' => 'Row has been synced to cache'
            ];
        } catch (\Exception $exception) {
            $response = [
                'type' => 'danger',
                'message' => $exception->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function duplicateAttributes()
    {
        $queryvalue = "(SELECT COUNT(row_id)AS cnt , row_id, created_at FROM attribute_data WHERE (`code`='email') GROUP BY row_id HAVING cnt>1)";
        $attributeList= \DB::select(\DB::raw($queryvalue));
        // dd($attributeList);
        return view('attributeData.attributeDataDuplicateList')->with('attributeList',$attributeList);
    }

    public function duplicateFix()
    {

        $queryvalue = "(SELECT COUNT(row_id)AS cnt , row_id,company_id, created_at FROM attribute_data WHERE (`code`='email') GROUP BY row_id HAVING cnt>1)";
        $attributeList= \DB::select(\DB::raw($queryvalue));


        CompanyAttributeData::fixAttributeData($attributeList);
        return redirect()->route('duplicates.attribute');
//           return $this->duplicateFix();
//        }else{
//
//            return $this->duplicateFix();
//
//        }
    }
}
