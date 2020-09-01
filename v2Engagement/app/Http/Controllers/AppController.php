<?php

namespace App\Http\Controllers;

use App\Api\App\App;
use App\Apps;
use App\Helpers\CommonHelper;
use App\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Api\Response\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController extends Controller
{

    protected $app;
    protected $request;
    protected $response;

    /*
     * Initializing protected variables in constructor
     */
    public function __construct(Response $response, App $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    public function index(Request $request)
    {

        return view('app.appListing');
    }

    public function delete_app(Request $request)
    {

        $companyId = Auth::user()->id;
        $id = $request->id;
        $result = DB::table('app')
            ->where('id', $id)->where('company_id', '=', $companyId)->update(['is_deleted' => 1]);
        if ($result) {
            return $this->response->success();
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function fetch(Request $request)
    {

        $companyId = Auth::user()->id;
        list($totalData, $totalFiltered, $campaignListing) = $this->app->appListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $campaignListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign listing'
        ]);
    }

    public function crud(Request $request)
    {
        $companyId = Auth::user()->id;
        $id = $request->id;
        if ($id == 0) {
            $appObj = DB::table("app")->find($id);
            return view('app.create', ["appObj" => $appObj]);
        } else {
            $appObj = DB::table("app")->where('company_id', '=', $companyId)->find($id);
            if ($appObj) {
                return view('app.create', ["appObj" => $appObj]);
            } else {
                abort(403, 'Unauthorized');
            }
        }
    }

    public function submit(Request $request)
    {

        $companyId = Auth::user()->id;
        //general information
        $dataToSave = [];
        $allowedMimeType = ["image/png", "image/jpeg", "image/gif", "image/psd", "image/bmp"];

        $disk = Storage::disk('app');
        //$disk = Storage::disk('s3');
        $companyDir = "company_" . auth()->user()->id;
        $appName = $request->appName;
        $appId = $request->appID;
        $platform = $request->platform;
        if (!$request->id && CommonHelper::checkAppDuplication($appName, $appId, $platform, $companyId)) {

            return new JsonResponse(array(
                "status" => 500,
                "message" => "App Already exist",
            ));
        }


        if ($request->hasFile('appLogo')) {
            $file = $request->file('appLogo');
            if (in_array($file->getMimeType(), $allowedMimeType)) {

                /**
                 * @var $diskS3 FilesystemAdapter
                 */
                $diskS3 = Storage::disk("s3");
                if ($request->id) {

                    $app = Apps::find($request->id);
                    $diskS3->delete($app->logo);
                }
                $extension = $file->getClientOriginalExtension();
                $fileName = $companyId . '-' . time() . '.' . $extension;
                $fileName = $companyDir . '/app/logo/' . $fileName;
                $diskS3->put($fileName, File::get($file), 'public');
                $dataToSave['logo'] = $fileName;
            }
        }

        $dataToSave['is_sandbox'] = $request->get('is_sandbox') ? 1 : 0;
        $dataToSave['name'] = trim($appName);
        $dataToSave['app_id'] = trim($appId);
        $dataToSave['description'] = $request->description;
        $dataToSave['company_id'] = $companyId;
        $dataToSave['is_active'] = isset($request->is_active) ? 1 : 0;
        $dataToSave['is_deleted'] = 0;
        if (!$request->id) {
            $dataToSave['created_by'] = $companyId;
            $dataToSave['created_at'] = new \DateTime();
        }
        $dataToSave['modified_by'] = $companyId;
        $dataToSave['modified_at'] = new \DateTime();
        $dataToSave['platform'] = $platform;
        
        $companyDirDev='';
        
        if ($request->hasFile('companyFile2')) {
            $file = $request->file('companyFile2');
            $fileName = $file->getClientOriginalName();

            $companyDirDev = $companyDir . '/pem/dev/';

            if (!$disk->exists($companyDirDev)) {
                $disk->makeDirectory($companyDirDev);
            }

            $fileName = $companyDirDev . $fileName;

            $disk->put($fileName, File::get($file));
            //$disk->put($fileName, File::get($file), "public");

            $dataToSave['ios_cert_dev'] = $fileName;
        }
        if ($request->hasFile('companyFile1')) {

            $file = $request->file('companyFile1');
            $fileNameLive = $file->getClientOriginalName();
            $companyDirProd = $companyDir . '/pem/production/';

            if (!$disk->exists($companyDirDev)) {
                $disk->makeDirectory($companyDirDev);
            }

            $fileNameLive = $companyDirProd . $fileNameLive;

            $disk->put($fileNameLive, File::get($file));
            //$disk->put($fileNameLive, File::get($file), "public");

            $dataToSave['ios_cert_live'] = $fileNameLive;
        }

        //if ($request->Passphrase) {
        //$dataToSave['ios_passphrase'] = $request->Passphrase;
        //}
        if ($request->fireBaseKey) {
            $dataToSave['firebase_server_api_key'] = $request->fireBaseKey;

        }
        $id = null;
        if ($request->id) {

            $id = $request->id;
            DB::table('app')->where("id", $request->id)->update($dataToSave);
        } else {
            $id = DB::table('app')->insertGetId($dataToSave);
        }

        return new JsonResponse(array(
            "status" => 200,
            "message" => "General Information Save",
            "id" => $id,
        ));


    }
}
