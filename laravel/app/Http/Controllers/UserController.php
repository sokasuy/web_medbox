<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function users()
    {
        return view('auth.users');
    }

    public function getUsersList(Request $request)
    {
        $data = User::getDataListUser();
        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }

    public function addUser()
    {
        return view('auth.adduser');
    }
}
