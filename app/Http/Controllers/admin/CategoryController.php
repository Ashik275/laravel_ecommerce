<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


// use Image;



class CategoryController extends Controller
{
    //
    public function index(Request $req)
    {
        $categories = Category::latest();

        if (!empty($req->get('keyword'))) {
            $categories =   $categories->where('name', 'like', '%' . $req->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);

        return view('admin.category.list', [
            'categories' => $categories
        ]);
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $req)
    {   

     
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->showhome = $req->showhome;
            $category->save();

            ## Save Image
            if (!empty($req->image_id)) {
                $tempImage = TempImage::find($req->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sPath, $dPath);

                //Generate Image thumbnil
                // $dPath= public_path().'/uploads/category/thumbnil/'.$newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->save($dPath);


                $category->image = $newImageName;
                $category->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return view('admin.category.index');
        } else {
            return view('admin.category.edit', [
                'category' => $category
            ]);
        }
    }
    public function update($categoryId, Request $req)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'errors' => "Category not found",
            ]);
        }
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);
        if ($validator->passes()) {
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->showhome = $req->showhome;
            $category->save();

            ##delete old image
            $oldImage = $category->image;
            ## Save Image
            if (!empty($req->image_id)) {
                $tempImage = TempImage::find($req->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sPath, $dPath);

                //Generate Image thumbnil
                // $dPath= public_path().'/uploads/category/thumbnil/'.$newImageName;
                // $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->save($dPath);


                $category->image = $newImageName;
                $category->save();

                ## delete the old image on update
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }
            return response()->json([
                'status' => true,
                'message' => 'Category Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function statusUpdate($categoryId)
    {
        $category = Category::find($categoryId);
        // Check if the category is found
        if ($category) {
            $category->status = $category->status == 1 ? 0 : 1;

            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Category Status Updated Successfully'
            ]);
        }
    }
    public function destroy($categoryId, Request $req)
    {
        $category = Category::find($categoryId);
        if (empty($categoryId)) {
            return redirect()->route('categories.index');
        }
        ## delete the old image on update
        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
}
