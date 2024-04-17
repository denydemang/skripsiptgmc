<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function uploadFile($file): string{

        $extension = $file->getClientOriginalExtension();
        $filename = time().'.'.$extension;
        $file->move('uploads/' , $filename);

        return $filename;

    }
    public function downloadFile($fileName){
        try {
            
            $path =  public_path('uploads/'. $fileName);
            return response()->download($path)->deleteFileAfterSend(false);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
    public function deleteFile($filename){
        try {
            $path =  public_path('uploads/'. $filename);

            if (file_exists($path)) {
                unlink($path);
            } 
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
