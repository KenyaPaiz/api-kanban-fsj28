<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 * schema="User",
 * title="User",
 * description="User model (public view)",
 * @OA\Property(property="id", type="integer", readOnly="true", example=1),
 * @OA\Property(property="name", type="string", example="Alice Johnson"),
 * @OA\Property(property="email", type="string", format="email", example="alice@example.com")
 * )
 *
 * @OA\Tag(
 * name="users",
 * description="User management and task association"
 * )
 */
class UserController extends Controller
{
    //obtener usuarios
    /**
     * @OA\Get(
     * path="/users",
     * tags={"users"},
     * summary="Get all users",
     * description="Retrieves a list of all system users (name, email, and ID).",
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User"))
     * )
     * )
     * )
     */
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

    //mostrar tareas de un usuario autenticado
    /**
     * @OA\Get(
     * path="/user/tasks",
     * tags={"users"},
     * summary="Get tasks for authenticated user",
     * description="Retrieves all tasks assigned to the currently authenticated user. Requires authentication.",
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized (Missing or invalid token)"
     * ),
     * @OA\Response(
     * response=404,
     * description="No tasks found for the authenticated user",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="No tasks found for the authenticated user")
     * )
     * )
     * )
     */
    public function tasksByAuthenticatedUser(Request $request){
        $user = $request->user(); //usuario autenticado (objeto)
        $tasks = Task::where('user_id', $user->id)->get(); //[]

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found for the authenticated user'], 200);
        }

        return response()->json($tasks, 200);
    }
}
