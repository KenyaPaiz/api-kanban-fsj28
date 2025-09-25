<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //metodo para obtener todas las tareas
    public function index(){
        //SELECT t.*, u.name FROM tasks as t inner join users as u on u.id = t.user_id;
        $tasks = Task::join('users as u','u.id','=','tasks.user_id')->select('tasks.id','tasks.title','tasks.description','tasks.status','tasks.priority','tasks.due_date','tasks.user_id', 'u.name as user')->get();

        return response()->json(["data" => $tasks], 200);
    }

    //registrando una tarea (enviar datos)
    public function store(StoreTaskRequest $request){
        //422 Unprocessable Entity
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

    //obtener una tarea en especifico
    public function show($taskId){

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => 'required|exists:tasks,id'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::find($taskId); //devuelve un objeto
        return response()->json($task, 200);
    }

    //metodo para filtrar estado y prioridad de tareas
    public function filterStatusOrPriority(Request $request){

        $status = $request->query('status');
        $priority = $request->query('priority');

        $validator = Validator::make(
            ['status' => $status, 'priority' => $priority], 
            [
                'status' => 'nullable|in:pendiente,en proceso,completada',
                'priority' => 'nullable|in:baja,media,alta',
            ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //construyendo las consultas
        $query = Task::query();

        if($status){
            //SELECT * FROM tasks WHERE status = "en proceso"
            $query->where('status',$status);
        }

        if($priority){
            //SELECT * FROM tasks WHERE priority = "alta"
            $query->where('priority',$priority);
        }

        //devolviendo la informacion de las tareas (con o sin estado/prioridad)
        $tasks = $query->select('title','description','status','priority','due_date','user_id')->get(); //[]

        if($tasks->isEmpty()){
            return response()->json(["message" => "Por el momento no hay tareas"], 204);
        }

        return response()->json($tasks, 200);
    }

    
    public function update(Request $request, $taskId){
        //encontrar la tarea en base el id
        $task = Task::find($taskId);
        $task->update($request->all());
        return response()->json(["message" => "Task updated successfully"], 200);
    }

    //devolver cuantos dias falta o si se paso del limite de la fecha
    public function remaininDays($taskId){

    }
}
