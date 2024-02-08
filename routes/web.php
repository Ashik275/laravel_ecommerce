<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop',[ShopController::class,'index'])->name('front.shop');

######################## admin panel routing ##############################
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logOut'])->name('admin.logout');

        ## Category Create
        Route::get('/categories/index',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::get('/categories/{create}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');
        Route::get('/categories/{category}',[CategoryController::class,'statusUpdate'])->name('categories.status');

        ##Sub Category Create
        Route::get('/subcategories/index',[SubCategoryController::class,'index'])->name('subcategories.index');
        Route::get('/subcategories/create',[SubCategoryController::class,'create'])->name('subcategories.create');
        Route::get('/subcategories/{subcategory}/edit',[SubCategoryController::class,'edit'])->name('subcategories.edit');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('subcategories.store');
        Route::put('/subcategories/{subcategory}',[SubCategoryController::class,'update'])->name('subcategories.update');
        Route::delete('/subcategories/{subcategory}',[SubCategoryController::class,'destroy'])->name('subcategories.delete');
        Route::get('/subcategories/{subcategory}',[SubCategoryController::class,'statusUpdate'])->name('subcategories.status');

        ##Brands Route
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::get('/brands/index',[BrandController::class,'index'])->name('brands.index');
        Route::post('/brands',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/{create}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandController::class,'update'])->name('brands.update');
        Route::get('/brands/{brand}',[BrandController::class,'statusUpdate'])->name('brands.status');
        Route::delete('/brands/{brand}',[BrandController::class,'destroy'])->name('brands.delete');

        ##Product Route
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::get('/product-subcategories/index',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');
        Route::post('/products',[ProductController::class,'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
        Route::delete('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');
        ##Temp Image Upload
        Route::post('/upload-temp-image',[TempImageController::class,'create'])->name('temp-images.create');
        Route::post('/product-images/update',[ProductImageController::class,'update'])->name('product-images.update');
        Route::delete('/product-images',[ProductImageController::class,'destroy'])->name('product-images.destroy');
     
        
        Route::get('/getSlug',function(Request $request){
            $slug='';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});
