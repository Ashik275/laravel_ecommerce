<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    //
    public function update(Request $request)
    {

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();

        $tempImageLocation = $image->getPathName();


        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = '';
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        // $request->image_array->move(public_path() . '/uploads/product', $imageName);
        $productImage->image = $imageName;
        $productImage->save();

        $sourcePath =  $tempImageLocation;
        $destPath = public_path().'/uploads/product/'. $imageName;

        File::copy($sourcePath, $destPath);
    }

    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

    // Delete File From Folder 
    File::delete(public_path().'/uploads/product/'.$productImage->image);
    $productImage->delete();

    return response()->json([
        'status' => true,
        'message' => 'Image Deleted Successfully'
    ]);

    }
}
