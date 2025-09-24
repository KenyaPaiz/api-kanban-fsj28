<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //obtener usuarios
    public function index(){
        $users = User::select('id','name','email')->get(); //[]
        return response()->json(["data" => $users], 200);
        // //select name, email from users
        // DB::table('users')->select('name','email')->get();

        // //ORM (object relational mappers) -> permite trabajar con metodos mapeados utilizando modelos
        // //select * from users
        // DB::table('users')->get(); //querybuilder (PHP)


        // User::all(); //ORM -> (esconde la abstraccion de la bd)
        // //SELECT name, email FROM users WHERE id = 5
        // DB::table('users')->select('name','email')->where('id',5)->get();

        // User::select('name','email')->where('id',5)->get();//ORM + QUERYBUILDER
        // //SELECT * FROM users WHERE id = 5
        // User::find(5); //save(), update(), create(),delete()

        // //SELECT campos FROM users WHERE name LIKE 'A%';
        // User::where('name','like','A%')->select()->get();
        
    }
}
