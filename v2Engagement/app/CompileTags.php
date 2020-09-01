<?php


namespace App;

use Illuminate\Support\Facades\DB;

trait CompileTags
{
    public static function tagsCount($filterType ,$userId = null,$deleteColumnName)
    {
        try {
            $tags = self::where($filterType, $userId)->where($deleteColumnName,false)->pluck('tags')->map(function ($item) {
                return explode(',', $item);
            })->flatten();
            $uniques = $tags->unique();
            $tagsCount = [];

            foreach ($uniques as $unique) {
                if($unique!="")
                $tagsCount[$unique] = collect($tags)->filter(function ($tag) use($unique) {
                    return ($tag === $unique) ? $tag : null;
                })->count();
            }

            $arraytoReturn = collect($tagsCount)->sortByDesc(function ($item) {
                return $item;

            })->toArray();
            //$arraytoReturn = array_chunk($arraytoReturn,10,true);
            $arraytoReturn = array_chunk($arraytoReturn,5,true);
            return $arraytoReturn[0];
        } catch (\Exception $exception) {
            //
        }

        return [];
    }

    public static function appCount($companyId)
    {

        return UserAttribute::where("company_id", $companyId)->select(DB::raw('app_name, COUNT(*) as cnt'))->groupBy("app_name")->get();
    }
}