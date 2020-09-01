<?php

namespace App\Engagment\Gallery;

use App\Engagment\Campaign\CampaignHandler;
use App\Engagment\Newsfeed\NewsfeedHandler;
use Carbon\Carbon;
use CommonHelper;

class Gallery
{
    protected $gallery;

    public function __construct(GalleryHandler $gallery, CampaignHandler $campaignHandler)
    {
        $this->gallery= $gallery;
    }

    public function galleryListing($request,$companyId)
    {

       list($totalData, $totalFiltered, $galleryListing) = $this->gallery->galleryListing($request, $companyId);

       $galleryListingSort = $this->galleryListingSort($galleryListing);

       return array($totalData,$totalFiltered,$galleryListingSort);
    }

    public function galleryListingSort($galleryListing)
    {

        $resposnseArr = [];
        foreach ($galleryListing as $item){

            list($width,$height,$size) = $this->getImageAttributes($item);
            $resposnseArr [] = [
                "id"=>$item->id,
                "url"=>$item->image_url,
                "image"=>"<img src=$item->image_url>",
                "name"=>$item->image_name,
                "dimensions"=>"Width: ".$width."<br>"."Height: ".$height,
                "size"=>$size,
                "date"=>$item->created_at->format('l jS \of F Y h:i:s A')
            ];
        }
        return $resposnseArr;
    }

    public function getImageAttributes($image)
    {

        try {
            list($width, $height) = getimagesize($image->image_url);
            $headers = get_headers($image->image_url, true);
            return array($width, $height, $headers['Content-Length'] / 1024);
        }catch (\Exception $e){

            return array(0, 0, 0);

        }
    }
}