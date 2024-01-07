<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AffiliateController;

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
// Route::get('/', [AuthController::class, 'login']);
// Route::post('/login', [AuthController::class, 'authLogin']);
// Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'adminLogin']);
Route::get('/sign-up', [AuthController::class, 'signUp']);
Route::post('/sign-up', [AuthController::class, 'register']);
Route::group(['middleware' => 'user'], function () {
    Route::get('user/dashboard', [AuthController::class, 'dashboard']);
    Route::get('user/transaction/list', [AuthController::class, 'list']);
    Route::get('user/transaction/add', [AuthController::class, 'add']);
    Route::post('user/transaction/add', [AuthController::class, 'insert']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::get('/admin', [AdminController::class, 'login']);
Route::post('admin/login', [AdminController::class, 'adminLogin']);
Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('admin/affiliate/list', [AdminController::class, 'list']);
    Route::get('admin/affiliate/add', [AdminController::class, 'add']);
    Route::post('admin/affiliate/add', [AdminController::class, 'insert']);
    Route::get('admin/affiliate/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/affiliate/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/affiliate/delete/{id}', [AdminController::class, 'delete']);

    Route::get('admin/logout', [AdminController::class, 'logout']);
});

Route::get('/affiliate', [AffiliateController::class, 'login']);
Route::post('affiliate/login', [AffiliateController::class, 'affiliateLogin']);
Route::group(['middleware' => 'affiliate'], function () {
    Route::get('affiliate/dashboard', [AffiliateController::class, 'dashboard']);
    Route::get('affiliate/sub-affiliate/list', [AffiliateController::class, 'list']);
    Route::get('affiliate/sub-affiliate/add', [AffiliateController::class, 'add']);
    Route::post('affiliate/sub-affiliate/add', [AffiliateController::class, 'insert']);
    Route::get('affiliate/sub-affiliate/edit/{id}', [AffiliateController::class, 'edit']);
    Route::post('affiliate/sub-affiliate/edit/{id}', [AffiliateController::class, 'update']);
    Route::get('affiliate/sub-affiliate/delete/{id}', [AffiliateController::class, 'delete']);

    Route::get('affiliate/logout', [AffiliateController::class, 'logout']);
});


// Route::get('admin/dashboard', function () {
//     return view('admin.dashboard');
// });
