<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Models\Comment;
use App\Models\Reply;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/',[HomeController::class,'index']);
Route::get('/redirect', [HomeController::class, 'redirect'])->middleware('auth', 'verified')->name('home');
Route::get('/category',[AdminController::class,'category'])->name('admin.category');
Route::post('add_category',[AdminController::class,'add_category']);
Route::get('/delete_category/{id}',[AdminController::class,'delete_category']);
Route::get('/view_product',[AdminController::class,'view_product'])->name('admin.view_product');
Route::post('add_product',[AdminController::class,'add_product']);
Route::get('/show_product',[AdminController::class,'show_product'])->name('admin.show_product');
Route::get('/delete_product/{id}',[AdminController::class,'delete_product']);
Route::get('/update_product/{id}',[AdminController::class,'update_product']);
Route::post('/update_comfirm_product/{id}',[AdminController::class,'update_confirm_product']);
Route::get('/detail_product/{id}',[HomeController::class,'detail_product']);
Route::post('/add_cart/{id}',[HomeController::class,'add_cart'])->name('add.cart');
Route::get('/show_cart',[HomeController::class,'show_cart']);
Route::delete('/remove_cart/{id}',[HomeController::class,'remove_cart']);
Route::get('/cash_order',[HomeController::class,'cash_order']);
Route::post('/add_comment',[HomeController::class,'add_comment']);
Route::post('/add_reply',[HomeController::class,'add_reply']);
Route::delete('/delete_comment/{id}',[HomeController::class,'delete_comment']);
Route::delete('/delete_reply/{id}',[HomeController::class,'delete_reply']);
Route::post('/update_reply/{id}',[HomeController::class,'update_reply']);
Route::post('/update_comment/{id}',[HomeController::class,'update_comment']);
Route::get('/order',[AdminController::class,'order']);
Route::get('/delivered/{id}',[AdminController::class,'delivered']);
Route::get('/print/{id}',[AdminController::class,'print']);

Route::middleware('auth')->group(function () {
    Route::post('/paypal/{totalprice}', [PaymentController::class, 'pay_cart'])->name('pay.cart');
});
Route::get('/paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');

Route::post('/search',[AdminController::class,'search']);
Route::get('/show_order',[HomeController::class,'show_order']);
Route::delete('/cancel_order/{id}',[HomeController::class,'cancel_order']);
Route::get('/search_product',[HomeController::class,'search_product']);
//Goggle
Route::get('auth/google',[HomeController::class,'googlepage']);
Route::get('auth/google/callback',[HomeController::class,'googlecallback']);
//Botman
use App\Http\Controllers\BotManController;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
