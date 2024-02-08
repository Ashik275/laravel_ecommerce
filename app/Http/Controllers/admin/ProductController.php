<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        $products = Product::latest()->with('product_images');

        if ($request->get('keyword') != "") {
            $products =  $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate(10);

        // if (!empty($req->get('keyword'))) {
        //     $categories =   $categories->where('name', 'like', '%' . $req->get('keyword') . '%');
        // }
        // $products = $products;
        $data['products'] = $products;

        return view('admin.products.list', $data);
    }
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.products.create', $data);
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'category_id' => 'required|numeric',
            'track_qty' => 'required|in:Yes,No',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            //save pic
            if (!empty($request->image_array)) {
               
                foreach($request->image_array as $temp_img_id) {

                    $tempImageInfo = TempImage::find($temp_img_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = '';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    // $request->image_array->move(public_path() . '/uploads/product', $imageName);
                    $productImage->image = $imageName;
                    $productImage->save();

                    $sourcePath = public_path() . '/temp/' .  $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/' . $imageName;

                    File::copy($sourcePath, $destPath);
                }
            }
            return response()->json([
                'status' => true,
                'message' => "Product Added Successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $data = [];
        $product = Product::find($id);

        $productImages = ProductImage::where('product_id', $product->id)->get();
        $data['product'] =  $product;
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['productImages'] = $productImages;

        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'category_id' => 'required|numeric',
            'track_qty' => 'required|in:Yes,No',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            return response()->json([
                'status' => true,
                'message' => "Product Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(([
                'status' => false,
                'notFound' => true
            ]));
        }
        $productImages = ProductImage::where('product_id', $id)->get();
        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/' . $productImage->image));
                // File::delete(public_path() . '/uploads/category/' . $category->image);
            }
            ProductImage::where('product_id', $id)->delete();
        }
        $product->delete();
        return response()->json([
            'status' => true,
            'message' => "Product Deleted Successfully"
        ]);
    }
}
