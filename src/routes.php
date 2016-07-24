<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(
    [
    'middleware' => ['admin',
    'acl'],
    'can' => 'view.role'],
    function () {
        Route::resource('role', '\Alientronics\FleetanyWebAdmin\Controllers\RoleController');
        Route::get('role/destroy/{id}', '\Alientronics\FleetanyWebAdmin\Controllers\RoleController@destroy');
        Route::post('permissions/create', '\Alientronics\FleetanyWebAdmin\Controllers\RoleController@createPermission');
    }
);
