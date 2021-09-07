<?php

use App\Http\Middleware\adminCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ReportsController;

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
Route::prefix('app')->middleware(adminCheck::class)->group(function(){
    Route::post('/create_user',[AdminController::class, 'createUser']);
    Route::post('/delete_user',[AdminController::class, 'deleteUser']);
    Route::post('/edit_user',[AdminController::class, 'editUser']);
    Route::get('/get_users',[AdminController::class, 'getUsers']);
    
});

Route::prefix('app')->group(function(){


Route::post('/login',[AuthController::class, 'login']);

Route::get('/get_cleared_creditor_date_range/{fromDate}/{toDate}',[ReportsController::class, 'getClearedCreditorDateRange']);
Route::get('/get_date_range/{fromDate}/{toDate}',[ReportsController::class, 'getDateRange']);


Route::get('/get_cleared_creditor_orders',[ReportsController::class, 'getClearedCreditorOrders']);
Route::get('/get_cleared_orders',[ReportsController::class, 'getClearedOrders']);


Route::get('/get_companies',[CompanyController::class, 'getCompanies']);
Route::post('/create_company',[CompanyController::class, 'createCompany']);
Route::post('/edit_company',[CompanyController::class, 'editCompany']);
Route::post('/delete_company',[CompanyController::class, 'deleteCompany']);



Route::post('/checkout_creditor_order',[OrderController::class, 'checkoutCreditorOrder']);
Route::get('/get_creditor_ready_orders',[OrderController::class, 'getCreditorReadyOrders']);
Route::post('/creditor_order_confirmed_by_cook/{order_id}',[OrderController::class, 'creditorOrderConfirmedByCook']);
Route::get('/get_requested_creditor_orders',[OrderController::class, 'getRequestedCreditorOrders']);
Route::post('/create_creditor_order',[OrderController::class, 'createCreditorOrder']);


Route::post('/clear_take_away_order/{order_id}',[OrderController::class, 'clearTakeAwayOrder']);
Route::get('/get_latest_requested_order',[OrderController::class, 'getLatestRequestedOrder']);
Route::post('/checkout_order',[OrderController::class, 'checkoutOrder']);
Route::post('/checkout_take_away_order',[OrderController::class, 'checkoutTakeAwayOrder']);
Route::get('/get_ready_orders',[OrderController::class, 'getReadyOrders']);
Route::post('/order_confirmed_by_cook/{order_id}/{order_type}',[OrderController::class, 'orderConfirmedByCook']);
Route::get('/get_requested_orders',[OrderController::class, 'getRequestedOrders']);
Route::post('/create_order_details',[OrderController::class, 'createOrderDetails']);


Route::post('/add_stock',[AdminController::class, 'addStock']);




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

});

Route::get('/logout',[AuthController::class, 'logout']);
Route::get('/',[HomeController::class, 'index']);
Route::any('{slug}', [HomeController::class, 'index'])->where('slug', '([A-z\d\-\/_.]+)?');

// Route::get('/', function () {
//     return view('welcome');
// });
