<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //metodo para iniciar sesion y obtener el token
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
    }
}
