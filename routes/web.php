<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;

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

Route::get('/cc', function () {
    Artisan::call('cache:clear');
    echo '<script>alert("cache clear Success")</script>';
});
Route::get('/ccc', function () {
    Artisan::call('config:cache');
    echo '<script>alert("config cache Success")</script>';
});
Route::get('/vc', function () {
    Artisan::call('view:clear');
    echo '<script>alert("view clear Success")</script>';
});
Route::get('/cr', function () {
    Artisan::call('route:cache');
    echo '<script>alert("route clear Success")</script>';
});
Route::get('/coc', function () {
    Artisan::call('config:clear');
    echo '<script>alert("config clear Success")</script>';
});
Route::get('/storage123', function () {
    Artisan::call('storage:link');
    echo '<script>alert("linked")</script>';
});





Route::get('admin', [AdminController::class, 'index']);
Route::post('admin/register', [AdminController::class, 'store'])->name('admin.register');
Route::get('admin/register', [AdminController::class, 'create']);
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::group(['middleware' => ['admin_auth']], function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']); 
    Route::get('admin/logout', function () {
        session()->forget('admin_login');
        session()->forget('logged_in_admin');
        return redirect('admin')->with('success', 'Successfully logged out');
    });
    Route::resource('admin/countries', CountryController::class);
    Route::resource('admin/timezones', TimezoneController::class);
    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/subcategories', SubcategoryController::class);
});
