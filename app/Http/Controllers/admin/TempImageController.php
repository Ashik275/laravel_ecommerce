<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;



class TempImageController extends Controller
{
    //
    public function create(Request $request)
    {
        $image = $request->image;
   
        if (!empty($image)) {
            ##Getting image extension
            $extension = $image->getClientOriginalExtension();
            $originalName = $image->getClientOriginalName();

            $newName =  $originalName .time() . '.' . $extension;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newName);

            //Generate Image thumbnil
            // $sPath= public_path() . '/temp'.$newName;
            // $dPath= public_path() . '/temp/thumb'.$newName;
            // $img = Image::make($sPath);
            // $img->fit(300, 300);
            // $img->save($dPath);


            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'image_path' => asset('/temp/'.$newName),
                'message' => 'Image Uploaded Succcessfully',
            ]);
        }
    }
}
