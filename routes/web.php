<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

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

Route::post('/checkout_order',[OrderController::class, 'checkoutOrder']);
Route::get('/get_ready_orders',[OrderController::class, 'getReadyOrders']);
Route::post('/order_confirmed_by_cook/{order_id}',[OrderController::class, 'orderConfirmedByCook']);
Route::get('/get_requested_orders',[OrderController::class, 'getRequestedOrders']);
Route::post('/create_order_details',[OrderController::class, 'createOrderDetails']);

Route::get('/get_category_id/{category_id}',[AdminController::class, 'getCategoryId']);
Route::get('/get_items_for_pos',[AdminController::class, 'getItemsForPos']);
Route::get('/get_empty_and_unpaid_tables',[AdminController::class, 'getEmptyAndUnpaidTables']);
Route::get('/get_all_tables',[AdminController::class, 'getAllTables']);
Route::post('/create_table',[AdminController::class, 'createTable']);
Route::post('/create_role',[AdminController::class, 'createRole']);
Route::get('/get_roles',[AdminController::class, 'getRoles']);
Route::post('/delete_item',[AdminController::class, 'deleteItem']);
Route::post('/edit_item',[AdminController::class, 'editItem']);
Route::get('/get_items',[AdminController::class, 'getItems']);
Route::post('/create_item',[AdminController::class, 'createItem']);
Route::post('/delete_category',[AdminController::class, 'deleteCategory']);
Route::post('/edit_category',[AdminController::class, 'editCategory']);
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
