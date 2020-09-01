<?php
/**
 * Created by PhpStorm.
 * User: Hassan Raza
 * Date: 12/04/2018
 * Time: 10:42 AM
 */

namespace App\Api\App;

use App\Api\Response\Response;
use Carbon\Carbon;

class App
{
    protected $response;
    protected $app;
    protected $timenow;
    public function __construct(Response $response,AppHandler $app)
    {
        $this->app=$app;
        $this->response = $response;
        $this->timenow = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
    }



    public function appListing($request, $companyId){

        return list($totalData, $totalFiltered, $campaignListing) = $this->app->appListing($request, $companyId);

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
    public function store_lookup($data){


        unset($data['_token']);
        unset($data['button']);
        $data['code'] = strtoupper(str_replace(' ','_',$data['code']));
        if(isset($data['id'])) {

            $data = array_merge($data, [
                'modified_date' => $this->timenow
            ]);
            $resp = $this->lookup->update_lookup($data,$data['id']);

        }else{

            $data = array_merge($data, [
                'created_date' => $this->timenow,
                'modified_date' => $this->timenow
            ]);
            $resp = $this->lookup->store_lookup($data);
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