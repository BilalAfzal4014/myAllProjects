<?php

namespace App\Components;

use App\CampaignVariant;

class DistributionVariants
{
    /**
     * divide all provided users for a campaign
     * into variant based chunks
     *
     * @param @array $rowId
     * @param @int $campaignId
     *
     * @return array
     * */
    public static function distribution($rowId, $campaignId)
    {
        try {
            $skip = 0;
            $totalUsers = count($rowId);
            $variantItr = 1;
            $variantsDistribution = CampaignVariant::where("campaign_id", $campaignId)
                                                        ->select("id","distribution")->orderBy('id', 'asc')->get();
            $variants = [];
            foreach ($variantsDistribution as $distribution) {
                $variantUserCount = ceil(($distribution->distribution / 100) * $totalUsers);
                $currentVariant = [
                    'id' => $distribution->id,
                    'row_ids' => []
                ];
                for ($i = 0; $i < $variantUserCount; $i++) {
                    if (isset($rowId[$skip + $i]))
                        $currentVariant['row_ids'][] = $rowId[$skip + $i];
                }
                $skip = $skip + $variantUserCount;
                $variants[] = $currentVariant;
                $variantItr++;
            }
            return $variants;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}