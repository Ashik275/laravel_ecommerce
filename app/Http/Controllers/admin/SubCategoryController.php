<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $req)
    {
        $subcategories = SubCategory::latest();

        if (!empty($req->get('keyword'))) {
            $subcategories =   $subcategories->where('name', 'like', '%' . $req->get('keyword') . '%');
            // $subcategories =   $subcategories->orwhere('categories.name', 'like', '%' . $req->get('keyword') . '%');
        }
        $subcategories = $subcategories->paginate(10);

        return view('admin.subcategory.list', [
            'subcategories' => $subcategories
        ]);
    }
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('admin.subcategory.create', [
            'categories' => $categories
        ]);
    }
    public function store(Request $req)
    {
        // Initializes a validation using Laravel's Validator class.
        // Validator::make() is used to create a new validator instance.
        // The first argument ($req->all()) passes all data from the request to the validator.
        // The second argument is an array defining validation rules for specific fields.
        // In this case, it's validating that the 'name', 'slug', 'category_id', and 'status' fields are required.
        // Additionally, it checks if the 'slug' is unique in the 'sub_categories' table.

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category_id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            $sub_category = new SubCategory();
            $sub_category->name = $req->name;
            $sub_category->category_id = $req->category_id;
            $sub_category->slug = $req->slug;
            $sub_category->status = $req->status;
            $sub_category->showhome = $req->showhome;
            $sub_category->save();

            return response()->json([
                'status' => true,
                'message' => 'Sub Category added successfully'
            ]);
        } else {
            //response()->json([...]):

            // This is a Laravel helper function used to create a JSON response.
            // It takes an array as an argument, which will be converted to JSON format.
            // 'status' => false:

            // It includes a key-value pair in the JSON array, indicating the status of the response.
            // In this case, the 'status' key is set to false, suggesting that the request or operation did not succeed.
            // 'errors' => $validator->errors():

            // It includes a key-value pair for 'errors' in the JSON array.
            // The 'errors' key is assigned the result of $validator->errors().
            // $validator->errors() retrieves an instance of the MessageBag class containing validation error messages.
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($subcategoryId, Request $request)
    {
        $subcategory = SubCategory::find($subcategoryId);
        $categories = Category::where('status', 1)->get();
        if (empty($subcategory)) {
            return view('admin.subcategory.index');
        } else {
            return view('admin.subcategory.edit', [
                'subcategory' => $subcategory,
                'categories' => $categories
            ]);
        }
    }
    public function update($subcategoryId, Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category_id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            $sub_category = SubCategory::find($subcategoryId);;
            $sub_category->name = $req->name;
            $sub_category->category_id = $req->category_id;
            $sub_category->slug = $req->slug;
            $sub_category->status = $req->status;
            $sub_category->showhome = $req->showhome;
            $sub_category->save();

            return response()->json([
                'status' => true,
                'message' => 'Sub Category added successfully'
            ]);
        } else {
            //response()->json([...]):

            // This is a Laravel helper function used to create a JSON response.
            // It takes an array as an argument, which will be converted to JSON format.
            // 'status' => false:

            // It includes a key-value pair in the JSON array, indicating the status of the response.
            // In this case, the 'status' key is set to false, suggesting that the request or operation did not succeed.
            // 'errors' => $validator->errors():

            // It includes a key-value pair for 'errors' in the JSON array.
            // The 'errors' key is assigned the result of $validator->errors().
            // $validator->errors() retrieves an instance of the MessageBag class containing validation error messages.
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function statusUpdate($subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);
        // Check if the category is found
        if ($subcategory) {
            $subcategory->status = $subcategory->status == 1 ? 0 : 1;

            $subcategory->save();

            return response()->json([
                'status' => true,
                'message' => 'Sub Category Status Updated Successfully'
            ]);
        }
    }
    public function destroy($subcategoryId, Request $req)
    {
        $subcategory = SubCategory::find($subcategoryId);
        if (empty($subcategoryId)) {
            return redirect()->route('subcategories.index');
        }


        $subcategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sub Category Deleted Successfully'
        ]);
    }
}
