<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        try {
            $id = $request->get('id');

            // $user = new User();
            // $user->updateValidator($request->all())->validate();

            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return response()->json(
                array(
                    'status' => 'ok',
                    'msg' => "<div class='fas fa-bell alert alert-success' style='margin-bottom:10px;'> User '" . $user->name . "' data updated</div>"
                ),
                200
            );
        } catch (\PDOException $e) {
            return response()->json(
                array(
                    'status' => 'error',
                    'msg' => $user->name . " gagal diupdate"
                ),
                200
            );
        }
    }
}
