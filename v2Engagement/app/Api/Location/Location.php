<?php
/**
 * Created by PhpStorm.
 * User: Hassan Raza
 * Date: 12/04/2018
 * Time: 10:42 AM
 */

namespace App\Api\Location;

use App\Api\Response\Response;
use Carbon\Carbon;

class Location
{
    protected $response;
    protected $location;
    protected $timenow;
    public function __construct(Response $response,LocationHandler $location)
    {
        $this->location=$location;
        $this->response = $response;
        $this->timenow = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
    }



    public function locationListing($request){

        return list($totalData, $totalFiltered, $campaignListing) = $this->location->locationListing($request);

    }
    public function get_all_lookup_listing($parent = null)
    {
        $data = $this->lookup->get_all_lookup_query($parent);
        return $data;
    }


    public function delete_lookup($id){
        $this->lookup->delete_lookup($id);
        return $this->response->success();
    }
    public function store_location($data){


        unset($data['_token']);
        unset($data['button']);
        $data['is_active'] = 1;
        $data['radius'] = (double)round((double)$data['radius'],6);
        if(isset($data['id'])) {

            $data = array_merge($data, [
                'updated_at' => $this->timenow
            ]);
            $resp = $this->location->update_lookup($data,$data['id']);

        }else{

            $data = array_merge($data, [
                'created_at' => $this->timenow,
                'updated_at' => $this->timenow
            ]);
            $resp = $this->location->store_location($data);
        }


        if(!$resp['error']){
            return $this->response->success();
        }else{
            return $this->response->bad_request($resp);
        }
    }
    public function edit_lookup($id){
        $resp = $this->lookup->edit_lookup($id);
        if($resp){
            return $this->response->success($resp);
        }else{
            return $this->response->bad_request($resp);
        }
    }
    public function update_lookup($data, $id){
        $data = array_merge($data, ['updated_date'=> $this->timenow]);
        $resp = $this->lookup->update_lookup($data, $id);
        if($resp){
            return $this->response->success();
        }else{
            return $this->response->bad_request($resp);
        }
    }

}