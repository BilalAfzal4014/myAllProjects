<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use Carbon\Carbon;
use App\User;
use App\Gallery;
use Auth;
//Enables us to output flash messaging
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Session;
use Intervention\Image\ImageManager;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use \EngSendgrid;
use Symfony\Component\HttpFoundation\JsonResponse;

class GalleryController extends Controller
{

    private $gallery;

    public function __construct(\App\Engagment\Gallery\Gallery $gallery)
    {

        $this->gallery = $gallery;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gallery.index');
    }

    public function fetch(Request $request)
    {

        $companyId = Auth::user()->id;
        list($totalData, $totalFiltered, $galleryListing) = $this->gallery->galleryListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Gallery listing'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Newsfeed $newsfeed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $companyDir = "company_" . auth()->user()->id . '/gallery';
        $id = $request->id;
        $companyId=auth()->user()->id;
        $galleryObj = Gallery::where('company_id','=',$companyId)->find($id);
        if($galleryObj)
        {
            $imageName = $galleryObj->image_name;
            $galleryObj->is_deleted = 1;
            $galleryObj->save();
//            $galleryObj->delete();
//            $disk = Storage::disk('s3');
//            $disk->delete($companyDir.'/'.$imageName);
            return redirect()->route('gallery')
                ->with('flash_message', 'image successfully deleted.');
        }else{
            abort(403, 'Unauthorized');
        }


        /**
         * @var  $disk FilesystemAdapter
         */

    }

    public function do_upload(Request $request)
    {

        if ($request->hasFile('file')) {

            $imageUrl = CommonHelper::do_upload($request,'file');


            return new JsonResponse(array(
                "status" => 200,
                "image_url" => $imageUrl
            ));

        }
        return new JsonResponse(array(
            "status" => 500,
            "image_url" => 'error'
        ));
    }

    public function modalListing(Request $request)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            3 => 'created_at',
            1 => 'image_name',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $myQuery = DB::table('gallery')
            ->where('company_id', $companyId)
            ->where('is_deleted', 0);
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where('image_name', 'LIKE', "%{$search}%");
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->select('image_url as url', 'image_name as name', 'created_at')
            ->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get();
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();

        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Gallery listing'
        ]);

    }

    public function validateImagePath($image)
    {

        $chunks = explode('/', $image);
        return $chunks[1] . '/' . $chunks[2] . '/' . $chunks[3];
    }

    public function crop(Request $request)
    {
        $disk = Storage::disk('s3');

        if ($request->hasFile('croppedImage')) {

            $companyId = Auth::user()->id;
            $file = $request->file('croppedImage');
            $extension = $file->extension();

            $fileName = 'crop' . '-'.$companyId.'-' . time() . '.' . $extension;
            $companyDir = "company_".auth()->user()->id.'/gallery';
            if (!$disk->exists($companyDir)) {
                $disk->makeDirectory($companyDir);
            }
//            $fileName11 = $companyDir.'/'.$fileName;
//            $imagePath = url(Storage::url('uploads/gallery/' . $companyDir . '/' . $fileName));

            $disk->put($companyDir.'/'.$fileName, File::get($file),'public');

            $imagePath = $disk->url($companyDir.'/'.$fileName);
            $dataToSave['logo'] = $fileName;
            $image_url = $imagePath;
            $newImageObj = new Gallery;
            $newImageObj->company_id = $companyId;
            $newImageObj->image_name = $fileName;
            $newImageObj->image_url = $image_url;
            $newImageObj->created_by = $companyId;
            $newImageObj->updated_by = $companyId;
            $newImageObj->save();

            return new JsonResponse(array(
                "status" => 200,
                "image_url" => $image_url
            ));

        }
        return new JsonResponse(array(
            "status" => 500,
            "image_url" => ""
        ));
    }

}
