<?php
use \App\Role;
use \App\User;
use \App\Permission;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


// Блок artisan
Route::get('ar/{id}', function($id)
{
    return \Artisan::call($id, []);
});


Route::group(['middleware' => 'web'], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('login22', 'Auth\AuthController@showLoginForm')->name('login');
    Route::post('login22', 'Auth\AuthController@login')->name('login.do');
    Route::get('logout22', 'Auth\AuthController@logout')->name('logout');


    // Password Reset Routes...
    Route::get('password22/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('reset.form');
    Route::post('password22/email', 'Auth\PasswordController@sendResetLinkEmail')->name('reset.email');
    Route::post('password22/reset', 'Auth\PasswordController@reset')->name('reset.do');
    Route::get('/home22', 'HomeController@index');

    Route::group(['prefix'=>'super', 'middleware'=>'super'],function(){

        Route::resource('user','UserController');

        Route::put('user/assign-role/{id}/{role_alias}','UserController@assignRole')->name('user.role.assign');
        Route::put('user/revoke-role/{id}/{role_alias}','UserController@revokeRole')->name('user.role.revoke');

        Route::resource('role','RoleController');

        Route::put('role/assign-permission/{id}/{permission_alias}','RoleController@assignPermission')->name('role.permission.assign');
        Route::put('role/revoke-permission/{id}/{permission_alias}','RoleController@revokePermission')->name('role.permission.revoke');
        Route::put('role/assign-user/{id}/{user_id}','RoleController@assignUser')->name('role.user.assign');
        Route::put('role/revoke-user/{id}/{user_id}','RoleController@revokeUser')->name('role.user.revoke');

        Route::resource('permission','PermissionController');

        Route::put('permission/assign-role/{id}/{role_alias}','PermissionController@assignRole')->name('permission.role.assign');
        Route::put('permission/revoke-role/{id}/{role_alias}','PermissionController@revokeRole')->name('permission.role.revoke');
    });
});
