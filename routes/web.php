<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('chitietsp', function () {
    return view('product');
});

Route::get('trangchu',[PageController::class,'index']);
Route::resource('xxx',PageController::class);
// Route::get('index',['as'=>'trangchu','user'=>'PageController@getIndex']);

// Giới thiệu
Route::get('Gioi-thieu',[
    'as' => 'about',
    'user'=> 'PageController@getGioiThieu'
]);
Route::get('Lien-he',[
    'as' => 'contacts',
    'user'=> 'PageController@getLienHe'
]);

Route::get('add-to-cart/{id}',[PageController::class,'addToCart'] )->name('banhang.addToCart');
Route::get('del-cart/{id}',[PageController::class,'delCartItem'] )->name('banhang.xoagiohang');
Route::get('checkout',[PageController::class,'getCheckout'] )->name('banhang.getdathang');
Route::post('checkout',[PageController::class,'postCheckout'] )->name('banhang.postdathang');//sau khi dat hang thanh cong
//   đăng lý
Route::get('dangky',[PageController::class,'getSignin'])->name('getsignin');
Route::post('dangky',[PageController::class,'postSignin'])->name('postsignin');
//đăngnhâp
Route::get('dangnhap',[PageController::class,'getLogin'])->name('getlogin');
Route::post('dangnhap',[PageController::class,'postLogin'])->name('postlogin');
// đăng xuất
Route::get('dangxuat',[PageController::class,'postLogout'])->name('postlogout');


/*------ phần quản trị ----------*/
Route::get('admin/dangnhap',[UserController::class,'getLogin'])->name('admin.getLogin');
Route::post('admin/dangnhap',[UserController::class,'postLogin'])->name('admin.postLogin');
Route::get('admin/dangxuat',[UserController::class,'getLogout']);

Route::group(['prefix'=>'admin','middleware'=>'adminLogin'],function(){
        Route::group(['prefix'=>'category'],function(){
            // admin/category/danhsach
            Route::get('danhsach',[CategoryController::class,'getCateList'])->name('admin.getCateList');
            // admin/category/them
            Route::get('them',[CategoryController::class,'getCateAdd'])->name('admin.getCateAdd');
            Route::post('them',[CategoryController::class,'postCateAdd'])->name('admin.postCateAdd');
            Route::get('xoa/{id}',[CategoryController::class,'getCateDelete'])->name('admin.getCateDelete');
            Route::get('sua/{id}',[CategoryController::class,'getCateEdit'])->name('admin.getCateEdit');
            Route::post('sua/{id}',[CategoryController::class,'postCateEdit'])->name('admin.postCateEdit');
        });

});