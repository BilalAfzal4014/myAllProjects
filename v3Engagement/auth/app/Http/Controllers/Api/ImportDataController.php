<?php

namespace App\Http\Controllers\Api;

use App\ImportData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportDataController extends Controller
{
    public function downloadSampleFile()
    {
        $pathToFile = storage_path('sample_att_data_file/sample-attribute-data-file.xlsx');

        return response()->download($pathToFile);
    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $fileName = $request->get('file_name');
        $allowedMimeType = ["xlsx"];
        $extension = $file->getClientOriginalExtension();

        if ($file->getSize() >= 8388608) {
            return response()->json([
                'status' => 'Max file size should be 8MB.'
            ]);
        }

        if (!in_array($extension, $allowedMimeType)) {
            return response()->json([
                'status' => 'type_err'
            ]);
        }

        $disk = Storage::disk('s3');

        $type = $extension;
        $companyDir = "app/assets";
        $temp = explode(".", $fileName);
        $newfilename = substr($temp[0], 0, 20) . '.' . $type; //end($temp);
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\_\-\.]/', '', basename($newfilename));

        if (!$disk->exists($companyDir)) {
            $disk->makeDirectory($companyDir);
        }

        $disk->put($companyDir . '/' . $fileName, File::get($file), 'public');

        $filePath = $disk->url($companyDir . '/' . $fileName);
        list($width, $height) = getimagesize($filePath);
        $headers = get_headers($filePath, true);

        $this->saveImportData($request, [
            'file_name' => $fileName,
            'path' => $filePath,
            'size' => $headers['Content-Length'] / 1024
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'File uploaded successfully',
            'imagePath' => $filePath,
            'filename' => $fileName,
            'width' => $width,
            'height' => $height,
            'size' => $headers['Content-Length'] / 1024
        ]);
    }

    private function saveImportData($request, $attributes)
    {
        $user = $request->user();

        return ImportData::create([
            'company_id' => $user->id,
            'actual_file_name' => $request->get('file_name'),
            'file_name' => $attributes['file_name'],
            'file_size' => $attributes['size'],
            'file_path' => $attributes['path'],
            'created_by' => $user->id
        ]);
    }
}
