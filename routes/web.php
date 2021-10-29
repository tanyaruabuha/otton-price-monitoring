<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
 * Main routes
 */
Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/productCount', 'HomeController@count')->middleware('auth');
Route::get('price-list-update', 'PriceListUpdate\PriceListUpdateController@index');

/*
 * Update price
 */
Route::post('edit-price', 'PriceEdit\PriceEditController@edit');

/*
 * Test
 */
Route::prefix('test')->group(function () {
    Route::get('links/{id}', 'TestController@links');
    Route::get('values/{id}', 'TestController@values');
});

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/home', 'HomeController@index')->name('home');
