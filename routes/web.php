<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('https://midilatin.com');
});



Route::any('/get_access_token', [App\Http\Controllers\ApiController::class, 'get_access_token']);
Route::any('/install_app_shopify', [App\Http\Controllers\ApiController::class, 'install_app_shopify']);

Route::get('/get_products', [App\Http\Controllers\ApiController::class, 'get_products']);

Route::any('api/v1/app', [App\Http\Controllers\ApiController::class, 'app'])->name('api.app');
Route::get('/get_customer_orders/{customer_id}', [App\Http\Controllers\ApiController::class, 'get_customer_orders']);

Route::get('/preview_license/{product_id}', [App\Http\Controllers\PDFController::class, 'license_preview']);
Route::get('/license/{product_id}/{order_id}/{unixtimestamp}', [App\Http\Controllers\PDFController::class, 'license']);

//Webhooks
Route::any('/webhooks/products_create', [App\Http\Controllers\WebhookController::class, 'products_create']);
Route::any('/webhooks/products_update', [App\Http\Controllers\WebhookController::class, 'products_update']);
Route::any('/webhooks/products_delete', [App\Http\Controllers\WebhookController::class, 'products_delete']);
