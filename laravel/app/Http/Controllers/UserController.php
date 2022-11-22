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
        // dd($data);
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

    public function changeUserPassword(Request $request)
    {
        //
        $id = $request->get('id');
        $data = User::find($id);

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => view('auth.changeuserpwdform', compact('data'))->render()
            ),
            200
        );
    }

    public function actionChangeUserPassword(Request $request)
    {
        $id = $request->get('id');

        $user = new User();
        $user->updateValidator($request->all())->validate();
    }
}
