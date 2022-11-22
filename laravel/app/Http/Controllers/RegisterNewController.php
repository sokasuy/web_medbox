<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Session;

class RegisterNewController extends Controller
{
    //
    public function actionRegister(Request $request)
    {
        $user = new User();
        $user->validator($request->all())->validate();

        event(new Registered($user = $user->create($request->all())));

        //TAMBAHAN BARU dari https://www.ayongoding.com/membuat-register-user-laravel/

        Session::flash('message', 'Penambahan user baru berhasil. User sudah aktif, silahkan login menggunakan email dan password.');
        return redirect(route('auth.users'));
    }
}
