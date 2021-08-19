<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

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
Route::prefix('app')->group(function(){

Route::post('/create_category',[AdminController::class, 'createCategory']);
Route::get('/get_categories',[AdminController::class, 'getCategories']);
Route::post('/upload',[AdminController::class, 'upload']);
Route::post('/delete_image',[AdminController::class, 'deleteImage']);
Route::post('/create_user',[AdminController::class, 'createUser']);
Route::post('/delete_user',[AdminController::class, 'deleteUser']);
Route::post('/edit_user',[AdminController::class, 'editUser']);
Route::get('/get_users',[AdminController::class, 'getUsers']);

});


Route::get('/',[HomeController::class, 'index']);
Route::any('{slug}', [HomeController::class, 'index'])->where('slug', '([A-z\d\-\/_.]+)?');

// Route::get('/', function () {
//     return view('welcome');
// });
