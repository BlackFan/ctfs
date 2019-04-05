<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DirectoryIterator;

class ImagesController extends Controller
{
    public function __construct()
    {
    }

    public function imagesList(Request $request) {
        $response = [];
        if($request->has('year')) {
            $year = $request->input('year');
            $imgPath = storage_path('app/'.$year.'/img/');
            $iterator = new DirectoryIterator($imgPath);
            foreach ($iterator as $fileInfo) {
                if($fileInfo->isDot()) continue;
                    $response[] = $fileInfo->getFilename();
            }
        }
        return response()->json($response);
    }

    public function image(Request $request) {
        if($request->has('year') && $request->has('img')) {
            $year = $request->input('year');
            $img = $request->input('img');
            $img = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $img);
            $imgPath = storage_path('app/'.$year.'/img/'.$img);

            if (file_exists($imgPath)) {
                return response()->download($imgPath);
            }
        }
        return '';

    }
}
