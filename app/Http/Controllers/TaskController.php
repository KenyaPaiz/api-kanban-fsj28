<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //registrando una tarea (enviar datos)
    public function store(Request $request){

        Validator::make([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ], [
            'user_id' => 'required|exists:users,id'
        ], [
            'user_id' => "El campo es obligatorio"
        ]);
        
        //instancia (new) -> Task
        // $task = new Task();
        // $task->title = $request->input('title');
        // $task->description = $request->input('description');
        // $task->status = $request->input('status');
        // $task->title = $request->input('title');
        // $task->title = $request->input('title');
        // $task->save(); //insert into..

        //insertamos una tarea de manera masiva
        Task::create($request->all()); //insert into..
        return response()->json(['message' => 'Task created successfully'], 201);
    }
}
