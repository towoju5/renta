<?php

use App\Http\Controllers\Admin\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function(){
      Route::get('get-all-users', [UsersController::class, 'get_all_users']);
});