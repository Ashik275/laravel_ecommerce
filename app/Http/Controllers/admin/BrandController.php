<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    //
    public function create()
    {
        return view('admin.brands.create');
    }
    public function index(Request $req)
    {
        $brands = Brand::latest();

        if (!empty($req->get('keyword'))) {
            $brands =   $brands->where('name', 'like', '%' . $req->get('keyword') . '%');
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.list', [
            'brands' => $brands
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $req->name;
            $brand->slug = $req->slug;
            $brand->status = $req->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return view('admin.brands.index');
        } else {
            return view('admin.brands.edit', [
                'brand' => $brand
            ]);
        }
    }

    public function update($brandId, Request $req)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'errors' => "Brand not found",
            ]);
        }
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
        ]);
        if ($validator->passes()) {
            $brand->name = $req->name;
            $brand->slug = $req->slug;
            $brand->status = $req->status;
            $brand->save();
            return response()->json([
                'status' => true,
                'message' => 'Brand Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function statusUpdate($brandId)
    {
        $brand = Brand::find($brandId);
        // Check if the category is found
        if ($brand) {
            $brand->status = $brand->status == 1 ? 0 : 1;

            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand Status Updated Successfully'
            ]);
        }
    }

    public function destroy($brandId, Request $req)
    {
        $brand = Brand::find($brandId);
        if (empty($brandId)) {
            return redirect()->route('brands.index');
        }

        $brand->delete();

        return response()->json([
            'status' => true,
            'message' => 'Brand Deleted Successfully'
        ]);
    }
}
