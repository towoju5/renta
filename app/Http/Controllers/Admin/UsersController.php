<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function get_all_users(Request $request){
        // get-all-users
        $users = User::with('wallets', 'properties')->get();
        return get_success_response($users);
    }
}
