<?php

namespace App\Http\Resources\V1\Lookups;

use App\Lookup;
use Illuminate\Http\Request;

class LookupFilters
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function process(Request $request)
    {
        $finalArray = [];
        $mainObject = (object)[];
        $mainObject->columns = 'parent_id';
        $mainObject->columnsAlias = 'Type';
        $mainObject->childern = [];
        $lookupFilters = Lookup::where('code', '=', 'ACTION_TRIGGERS')
            ->orWhere('code', '=', 'CONVERSION_TYPES')->get();

        if (count($lookupFilters) > 0) {
            for ($val = 0; $val < count($lookupFilters); $val++) {
                $obj = (object)[];
                $obj->parent = 'parent_id';
                $obj->value = $lookupFilters[$val]['id'];
                $obj->parentAlias = $lookupFilters[$val]['code'];
                $mainObject->childern[] = $obj;
            }

            $meta=[
                'status'=>'200',
                'message'=>'Filters Found'
            ];

            $finalArray[] = $mainObject;
        } else {
            $meta=[
                'status'=>'400',
                'message'=>'Unable to locate any filters'
            ];
            $finalArray = [];
        }

        return [
            'data' => $finalArray,
            'meta'=>$meta
        ];
    }
}
