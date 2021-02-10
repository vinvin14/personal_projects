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
Route::get('units', 'api\UnitController@index')->name('api.units');
Route::post('unit/create', 'api\UnitController@store')->name('api.unit.store');
Route::post('unit/{id}', 'api\UnitController@update')->name('api.unit.show');
Route::get('unit/delete/{id}', 'api\UnitController@destroy')->name('api.unit.destroy');

Route::get('faultcodes', 'api\FaultCodeController@index')->name('api.fc');
Route::post('faultcode/create', 'api\FaultCodeController@store')->name('api.fc.create');
Route::post('faultcode/{id}', 'api\FaultCodeController@update')->name('api.fc.show');
Route::get('faultcode/delete/{id}', 'api\FaultCodeController@destroy')->name('api.fc.destroy');

Route::get('systemtypes', 'api\SystemTypeController@index')->name('api.st');
Route::post('systemtype/create', 'api\SystemTypeController@store')->name('api.st.create');
Route::post('systemtype/{id}', 'api\SystemTypeController@update')->name('api.st.show');
Route::get('systemtype/delete/{id}', 'api\SystemTypeController@destroy')->name('api.st.destroy');

Route::get('boardtypes', 'api\BoardTypeController@index')->name('api.bt');
Route::post('boardtype/create', 'api\BoardTypeController@store')->name('api.bt.create');
Route::post('boardtype/{id}', 'api\BoardTypeController@update')->name('api.bt.show');
Route::get('boardtype/delete/{id}', 'api\BoardTypeController@destroy')->name('api.bt.destroy');

Route::get('typeofservices', 'api\TSController@index')->name('api.ts');
Route::post('typeofservice/create', 'api\TSController@store')->name('api.ts.create');
Route::post('typeofservice/{id}', 'api\TSController@update')->name('api.ts.show');
Route::get('typeofservice/delete/{id}', 'api\TSController@destroy')->name('api.ts.destroy');

Route::get('customers', 'api\CustomerController@index')->name('api.customer');
Route::post('customer/create', 'api\CustomerController@store')->name('api.customer.create');
Route::post('customer/{id}', 'api\CustomerController@update')->name('api.customer.show');
Route::get('customer/delete/{id}', 'api\CustomerController@destroy')->name('api.customer.destroy');

Route::get('purposes', 'api\PurposeController@index')->name('api.purposes');
Route::post('purpose/create', 'api\PurposeController@store')->name('api.purpose.create');
Route::post('purpose/{id}', 'api\PurposeController@update')->name('api.purpose.show');
Route::get('purpose/delete/{id}', 'api\PurposeController@destroy')->name('api.purpose.destroy');

Route::get('locations', 'api\LocationController@index')->name('api.locations');
Route::post('location/create', 'api\LocationController@store')->name('api.location.create');
Route::post('location/{id}', 'api\LocationController@update')->name('api.location.show');
Route::get('location/delete/{id}', 'api\LocationController@destroy')->name('api.location.destroy');

Route::get('fse', 'api\FEController@index')->name('api.fse');
Route::post('fse/create', 'api\FEController@store')->name('api.fse.create');
Route::get('fse/{id}', 'api\FEController@show')->name('api.fse.show');
Route::post('fse/update/{id}', 'api\FEController@update')->name('api.fse.update');
Route::get('fse/delete/{id}', 'api\FEController@destroy')->name('api.fse.destroy');

Route::get('/resources/components', 'api\ResourcesController@components')->name('resources.components');

Route::get('board/{id}', 'api\BoardController@show')->name('api.board_show');

Route::get('repair/{id}/boards/{type}', 'api\RepairController@boards')->name('api.repair.board_show');

Route::get('notifications', 'api\NotificationController@notificationCount')->name('api.notifications_count');
Route::get('notifications/info', 'api\NotificationController@notificationInfo')->name('api.notifications_info');

Route::get('resources/consigned/{partNumber}', 'api\ConsignedController@getByPartNum')->name('api.consigned.collection');

Route::get('collect/boards/byDate/{date}', 'api\BoardController@repaired_boards_by_date');
