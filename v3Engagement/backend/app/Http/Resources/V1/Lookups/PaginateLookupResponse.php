<?php

namespace App\Http\Resources\V1\Lookups;

use App\Lookup;
use Illuminate\Http\Request;

class PaginateLookupResponse
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function process(Request $request)
    {
        $queryChain = Lookup::leftjoin('lookup as l2', 'l2.id', '=', 'lookup.parent_id')
            ->where('lookup.level', '=', 'company')->where('lookup.deleted_at',NULL)
            ->where('lookup.app_group_id',$request->user()->currentAppGroup()->id);
        $totalCount = clone $queryChain;
        $totalCount = $totalCount->count();
        if ($request['sideFilters'] != null && $request['sideFilters'] != []) {
            $queryChain->where('lookup'.'.'.$request['sideFilters']['parent'],'=', $request['sideFilters']['value']);
        }
        if ($request['query'] != null) {
            $search = $request['query'];
            $columns = $request['columns'];
            $queryChain->where(function ($query) use ($search, $columns) {
                $query->where('lookup.code', 'LIKE', "%{$search}%");
                $query->orWhere('lookup.name', 'LIKE', "%{$search}%");
                $query->orWhere('l2.code', 'LIKE', "%{$search}%");
            });
        }
        $totalFiltered = clone $queryChain;
        $totalFiltered = $totalFiltered->count();
        isset($request["orderBy"]) ? $queryChain->orderBy($request["orderBy"], $request["ascending"] == 1 ? 'desc' : 'asc') : '';
        $data = $queryChain->offset(($request['page'] - 1) * $request['limit'])
            ->limit($request['limit'])
            ->get([
                "l2.code as parent",
                "l2.id as parent_id",
                "lookup.id as id",
                "lookup.code as code",
                "lookup.name as name",
                "lookup.created_at as created_at"
            ]);

        $meta = [
            'pages' => ceil($totalFiltered / $request['limit']),
            'page' => $request['page'],
            'total' => $totalFiltered,
        ];

        return [
            'meta' => $meta,
            'data' => $data
        ];
    }
    public function getLookupbyId($id)
    {
       $lookup=Lookup::where('id','=',$id)->first();
       return $lookup;
    }
}