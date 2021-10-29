<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * Home
 */
Route::get('get-all-products', 'ProductController@getAll');

/*
 * Manual update routes
 */
Route::post('truncate-and-get-all-product-id', 'PriceListUpdate\PriceListUpdateController@truncateAndGetAllProductId');
Route::post('add-product', 'PriceListUpdate\PriceListUpdateController@addProduct');


