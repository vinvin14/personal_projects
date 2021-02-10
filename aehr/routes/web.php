<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
//Route::get('password', function () {
//    return Hash::make('iloveaehr');
//});
Route::view('/', 'login')->name('login');
Route::get('logout', 'AccountController@logout')->name('logout');
Route::post('login2', 'AccountController@login')->name('login2');
//registration
Route::get('register', 'AccountController@register')->name('register');
Route::post('register', 'AccountController@register_save')->name('register.save');
Route::get('not_auth', function() {
    return view('not_authorized');
});
Route::middleware(['access'])->group(function () {
    Route::get('account-settings/account-details', 'AccountSettingController@index')->name('account.settings');
    Route::get('account-settings/password-details', 'AccountSettingController@password')->name('account.password');
    Route::post('account-settings/change-password', 'AccountSettingController@change_password')->name('account.change_password');

    Route::middleware(['superadmin'])->group(function () {

        Route::get('account-settings/notification-details', 'AccountSettingController@notification')->name('account.notification');
        Route::post('account-settings/notification-details/save/{id}', 'AccountSettingController@notification_save')->name('account.notification.save');


        Route::get('account-management', 'AccountManagementController@index')->name('account.management');
        Route::get('account-management/{id}', 'AccountManagementController@show')->name('account.management.show');
        Route::get('account-management/reset-password/{id}', 'AccountManagementController@reset')->name('account.management.password.reset');
        Route::post('account-management/update/{id}', 'AccountManagementController@approve')->name('account.management.approve');

        Route::group(['prefix' => 'reference'], function () {
            Route::get('units', 'ReferenceUnitController@index')->name('reference.units');
            Route::get('unit/update/{id}', 'ReferenceUnitController@update')->name('reference.unit.update');
            Route::get('unit/search', 'ReferenceUnitController@search')->name('reference.unit.search');
            Route::post('unit/update/{id}', 'ReferenceUnitController@upsave')->name('reference.unit.upsave');
            Route::get('unit/delete/{id}', 'ReferenceUnitController@destroy')->name('reference.unit.destroy');
            Route::get('unit/create', 'ReferenceUnitController@create')->name('reference.unit.create');
            Route::post('unit/store', 'ReferenceUnitController@store')->name('reference.unit.store');

            Route::get('faultcodes', 'ReferenceFCController@index')->name('reference.faultcodes');
            Route::get('faultcode/update/{id}', 'ReferenceFCController@update')->name('reference.faultcode.update');
            Route::get('faultcode/search', 'ReferenceFCController@search')->name('reference.faultcode.search');
            Route::post('faultcode/update/{id}', 'ReferenceFCController@upsave')->name('reference.faultcode.upsave');
            Route::get('faultcode/delete/{id}', 'ReferenceFCController@destroy')->name('reference.faultcode.destroy');
            Route::get('faultcode/create', 'ReferenceFCController@create')->name('reference.faultcode.create');
            Route::post('faultcode/store', 'ReferenceFCController@store')->name('reference.faultcode.store');

            Route::get('systemtypes', 'ReferenceSTController@index')->name('reference.systemtypes');
            Route::get('systemtype/update/{id}', 'ReferenceSTController@update')->name('reference.systemtype.update');
            Route::get('systemtype/search', 'ReferenceSTController@search')->name('reference.systemtype.search');
            Route::post('systemtype/update/{id}', 'ReferenceSTController@upsave')->name('reference.systemtype.upsave');
            Route::get('systemtype/delete/{id}', 'ReferenceSTController@destroy')->name('reference.systemtype.destroy');
            Route::get('systemtype/create', 'ReferenceSTController@create')->name('reference.systemtype.create');
            Route::post('systemtype/store', 'ReferenceSTController@store')->name('reference.systemtype.store');

            Route::get('boardtypes', 'ReferenceBTController@index')->name('reference.boardtypes');
            Route::get('boardtype/update/{id}', 'ReferenceBTController@update')->name('reference.boardtype.update');
            Route::get('boardtype/search', 'ReferenceBTController@search')->name('reference.boardtype.search');
            Route::post('boardtype/update/{id}', 'ReferenceBTController@upsave')->name('reference.boardtype.upsave');
            Route::get('boardtype/delete/{id}', 'ReferenceBTController@destroy')->name('reference.boardtype.destroy');
            Route::get('boardtype/create', 'ReferenceBTController@create')->name('reference.boardtype.create');
            Route::post('boardtype/store', 'ReferenceBTController@store')->name('reference.boardtype.store');

            Route::get('typeofservices', 'ReferenceTSController@index')->name('reference.typeofservices');
            Route::get('typeofservice/update/{id}', 'ReferenceTSController@update')->name('reference.typeofservice.update');
            Route::get('typeofservice/search', 'ReferenceTSController@search')->name('reference.typeofservice.search');
            Route::post('typeofservice/update/{id}', 'ReferenceTSController@upsave')->name('reference.typeofservice.upsave');
            Route::get('typeofservice/delete/{id}', 'ReferenceTSController@destroy')->name('reference.typeofservice.destroy');
            Route::get('typeofservice/create', 'ReferenceTSController@create')->name('reference.typeofservice.create');
            Route::post('typeofservice/store', 'ReferenceTSController@store')->name('reference.typeofservice.store');

            Route::get('customers', 'ReferenceCustomerController@index')->name('reference.customers');
            Route::get('customer/update/{id}', 'ReferenceCustomerController@update')->name('reference.customer.update');
            Route::get('customer/search', 'ReferenceCustomerController@search')->name('reference.customer.search');
            Route::post('customer/update/{id}', 'ReferenceCustomerController@upsave')->name('reference.customer.upsave');
            Route::get('customer/delete/{id}', 'ReferenceCustomerController@destroy')->name('reference.customer.destroy');
            Route::get('customer/create', 'ReferenceCustomerController@create')->name('reference.customer.create');
            Route::post('customer/store', 'ReferenceCustomerController@store')->name('reference.customer.store');

            Route::get('locations', 'ReferenceLocationController@index')->name('reference.locations');
            Route::get('location/update/{id}', 'ReferenceLocationController@update')->name('reference.location.update');
            Route::get('location/search', 'ReferenceLocationController@search')->name('reference.location.search');
            Route::post('location/update/{id}', 'ReferenceLocationController@upsave')->name('reference.location.upsave');
            Route::get('location/delete/{id}', 'ReferenceLocationController@destroy')->name('reference.location.destroy');
            Route::get('location/create', 'ReferenceLocationController@create')->name('reference.location.create');
            Route::post('location/store', 'ReferenceLocationController@store')->name('reference.location.store');

            Route::get('purposes', 'ReferencePurposeController@index')->name('reference.purposes');
            Route::get('purpose/update/{id}', 'ReferencePurposeController@update')->name('reference.purpose.update');
            Route::get('purpose/search', 'ReferencePurposeController@search')->name('reference.purpose.search');
            Route::post('purpose/update/{id}', 'ReferencePurposeController@upsave')->name('reference.purpose.upsave');
            Route::get('purpose/delete/{id}', 'ReferencePurposeController@destroy')->name('reference.purpose.destroy');
            Route::get('purpose/create', 'ReferencePurposeController@create')->name('reference.purpose.create');
            Route::post('purpose/store', 'ReferencePurposeController@store')->name('reference.purpose.store');

            Route::get('fse', 'ReferenceFSEController@index')->name('reference.fses');
            Route::get('fse/update/{id}', 'ReferenceFSEController@update')->name('reference.fse.update');
            Route::get('fse/search', 'ReferenceFSEController@search')->name('reference.fse.search');
            Route::post('fse/update/{id}', 'ReferenceFSEController@upsave')->name('reference.fse.upsave');
            Route::get('fse/delete/{id}', 'ReferenceFSEController@destroy')->name('reference.fse.destroy');
            Route::get('fse/create', 'ReferenceFSEController@create')->name('reference.fse.create');
            Route::post('fse/store', 'ReferenceFSEController@store')->name('reference.fse.store');

            Route::get('/reports', 'ReportsController@index')->name('reports');

            Route::post('/reports/{type}', 'ReportsController@generate')->name('reports.generate');
        });
    });

    Route::group(['prefix' => 'resources'], function () {
        Route::get('/', 'ConsumableController@index')->name('consumables');
        Route::get('consumable/create', 'ConsumableController@create')->name('consumable.create');
        Route::post('consumable/create', 'ConsumableController@store')->name('consumable.store');
        Route::get('consumable/update/{id}', 'ConsumableController@update')->name('consumable.update');
        Route::post('consumable/update/{id}', 'ConsumableController@upsave')->name('consumable.upsave');
        Route::get('consumable/delete/{id}', 'ConsumableController@destroy')->name('consumable.destroy')->middleware('superadmin');
        Route::get('consumable/search', 'ConsumableController@search')->name('consumable.search');
        Route::get('consumable/{id}', 'ConsumableController@show')->name('consumable.show');

        Route::get('components', 'ComponentController@index')->name('components');
        Route::get('component/create', 'ComponentController@create')->name('component.create');
        Route::post('component/create', 'ComponentController@store')->name('component.store');
        Route::get('component/search', 'ComponentController@search')->name('component.search');
        Route::get('component/{id}', 'ComponentController@show')->name('component.show');
        Route::get('component/update/{id}', 'ComponentController@update')->name('component.update');
        Route::post('component/update/{id}', 'ComponentController@upsave')->name('component.upsave');
        Route::get('component/delete/{id}', 'ComponentController@destroy')->name('component.destroy')->middleware('superadmin');

        Route::get('consigned', 'ConsignedController@index')->name('consigned');
        Route::get('consigned/create', 'ConsignedController@create')->name('consigned.create');
        Route::post('consigned/create', 'ConsignedController@store')->name('consigned.store');
        Route::get('consigned/search', 'ConsignedController@search')->name('consigned.search');
        Route::get('consigned/{id}', 'ConsignedController@show')->name('consigned.show');
        Route::get('consigned/collection/{partNumber}', 'ConsignedController@showCollected')->name('consigned.collection.show');
        Route::get('consigned/update/{id}', 'ConsignedController@update')->name('consigned.update');
        Route::post('consigned/update/{id}', 'ConsignedController@upsave')->name('consigned.upsave');
        Route::get('consigned/delete/{id}', 'ConsignedController@destroy')->name('consigned.destroy')->middleware('superadmin');

        Route::get('equipment', 'EquipmentController@index')->name('equipment');
        Route::get('equipment/create', 'EquipmentController@create')->name('equipment.create');
        Route::post('equipment/create', 'EquipmentController@store')->name('equipment.store');
        Route::get('equipment/search', 'EquipmentController@search')->name('equipment.search');
        Route::get('equipment/{id}', 'EquipmentController@show')->name('equipment.show');
        Route::get('equipment/collection/{partNumber}', 'EquipmentController@showCollected')->name('equipment.collection.show');
        Route::get('equipment/update/{id}', 'EquipmentController@update')->name('equipment.update');
        Route::post('equipment/update/{id}', 'EquipmentController@upsave')->name('equipment.upsave');
        Route::get('equipment/delete/{id}', 'EquipmentController@destroy')->name('equipment.destroy')->middleware('superadmin');

        Route::get('tools', 'ToolController@index')->name('tools');
        Route::get('tool/create', 'ToolController@create')->name('tool.create');
        Route::post('tool/create', 'ToolController@store')->name('tool.store');
        Route::get('tool/search', 'ToolController@search')->name('tool.search');
        Route::get('tool/{id}', 'ToolController@show')->name('tool.show');
        Route::get('tool/collection/{partNumber}', 'ToolController@showCollected')->name('tool.collection.show');
        Route::get('tool/update/{id}', 'ToolController@update')->name('tool.update');
        Route::post('tool/update/{id}', 'ToolController@upsave')->name('tool.upsave');
        Route::get('tool/delete/{id}', 'ToolController@destroy')->name('tool.destroy')->middleware('superadmin');
    });

    Route::group(['prefix' => 'movement'], function () {
        //equipment movement
        Route::get('equipment', 'MovementEquipmentController@index')->name('movement.equipment');
        Route::get('equipment/create', 'MovementEquipmentController@create')->name('movement.equipment.create');
        Route::post('equipment/create', 'MovementEquipmentController@store')->name('movement.equipment.store');
        Route::get('equipment/update/{id}', 'MovementEquipmentController@update')->name('movement.equipment.update');
        Route::get('equipment/{id}', 'MovementEquipmentController@show')->name('movement.equipment.show');
        Route::post('equipment/{id}', 'MovementEquipmentController@upsave')->name('movement.equipment.upsave');
        Route::get('equipment/revert/{id}', 'MovementEquipmentController@revert')->name('movement.equipment.revert')->middleware('superadmin');
        //consigned movement
        Route::get('consigned', 'MovementConsignedController@index')->name('movement.consigned');
        Route::get('consigned/create', 'MovementConsignedController@create')->name('movement.consigned.create');
        Route::post('consigned/create', 'MovementConsignedController@store')->name('movement.consigned.store');
        Route::get('consigned/update/{id}', 'MovementConsignedController@update')->name('movement.consigned.update');
        Route::get('consigned/{id}', 'MovementConsignedController@show')->name('movement.consigned.show');
        Route::post('consigned/{id}', 'MovementConsignedController@upsave')->name('movement.consigned.upsave');
        Route::get('consigned/revert/{id}', 'MovementConsignedController@revert')->name('movement.consigned.revert')->middleware('superadmin');
        //tools movement
        Route::get('tools', 'MovementToolController@index')->name('movement.tools');
        Route::get('tool/create', 'MovementToolController@create')->name('movement.tool.create');
        Route::post('tool/create', 'MovementToolController@store')->name('movement.tool.store');
        Route::get('tool/update/{id}', 'MovementToolController@update')->name('movement.tool.update');
        Route::get('tool/{id}', 'MovementToolController@show')->name('movement.tool.show');
        Route::post('tool/{id}', 'MovementToolController@upsave')->name('movement.tool.upsave');
        Route::get('tool/revert/{id}', 'MovementToolController@revert')->name('movement.tool.revert')->middleware('superadmin');

        Route::get('consumables', 'MovementConsumableController@index')->name('movement.consumables');
        Route::get('consumable/create', 'MovementConsumableController@create')->name('movement.consumable.create');
        Route::post('consumable/create', 'MovementConsumableController@store')->name('movement.consumable.store');
        Route::get('consumable/update/{id}', 'MovementConsumableController@update')->name('movement.consumable.update');
        Route::get('consumable/{id}', 'MovementConsumableController@show')->name('movement.consumable.show');
        Route::get('consumable/revert/{id}', 'MovementConsumableController@revert')->name('movement.consumable.revert')->middleware('superadmin');
        Route::post('consumable/{id}', 'MovementConsumableController@upsave')->name('movement.consumable.upsave');

        Route::get('components', 'MovementComponentController@index')->name('movement.components');
        Route::get('component/create', 'MovementComponentController@create')->name('movement.component.create');
        Route::post('component/create', 'MovementComponentController@store')->name('movement.component.store');
        Route::get('component/update/{id}', 'MovementComponentController@update')->name('movement.component.update');
        Route::get('component/{id}', 'MovementComponentController@show')->name('movement.component.show');
        Route::get('component/revert/{id}', 'MovementComponentController@revert')->name('movement.component.revert')->middleware('superadmin');
        Route::post('component/{id}', 'MovementComponentController@upsave')->name('movement.component.upsave');
    });

    Route::group(['prefix' => 'repairs'], function () {
        Route::get('/', 'RepairController@index')->name('repairs');
        Route::get('create', 'RepairController@create')->name('repair.create');
        Route::post('store', 'RepairController@store')->name('repair.store');
        Route::post('upsave/{id}', 'RepairController@upsave')->name('repair.upsave');
        Route::get('update/{id}', 'RepairController@update')->name('repair.update');
        Route::get('delete/{id}', 'RepairController@softDestory')->name('repair.destroy')->middleware('superadmin');
        Route::get('search', 'RepairController@search')->name('repair.search');
        Route::get('{id}', 'RepairController@show')->name('repair.show');
    });
    Route::group(['prefix' => 'board'], function () {
        Route::get('create/{motherBoard}', 'BoardController@create')->name('board.create');
        Route::post('create/{motherBoard}', 'BoardController@store')->name('board.store');
        Route::get('{motherRecord}/{id}', 'BoardController@show')->name('board.show');
        Route::get('update/{motherBoard}/{id}', 'BoardController@update')->name('board.update');
        Route::post('update/{motherBoard}/{id}', 'BoardController@upsave')->name('board.upsave');
        Route::post('replacement/{board}', 'BoardController@replacements')->name('board.replacement.create');
        Route::get('print/{mother_record}/{id}', 'BoardController@print_rma')->name('board.print');
    });


    Route::get('/notifications', 'NotificationController@index')->name('notifications');
    Route::get('/notifications/resolve/all', 'NotificationController@resolveAll')->name('notifications.resolveAll');
    Route::get('/notification/resolve/{id}', 'NotificationController@resolve')->name('notification.resolve');
    Route::get('dashboard', 'DashboardController@index');
});

