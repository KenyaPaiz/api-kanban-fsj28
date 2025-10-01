<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Schema(
 * schema="Task",
 * title="Task",
 * description="Task model",
 * @OA\Property(property="id", type="integer", readOnly="true", example=1),
 * @OA\Property(property="title", type="string", example="Implement Task API"),
 * @OA\Property(property="description", type="string", example="Develop CRUD operations for the Task resource."),
 * @OA\Property(property="status", type="string", enum={"pendiente", "en proceso", "completada"}, example="en proceso"),
 * @OA\Property(property="priority", type="string", enum={"baja", "media", "alta"}, example="alta"),
 * @OA\Property(property="due_date", type="string", format="date", example="2025-10-30"),
 * @OA\Property(property="user_id", type="integer", example=5),
 * @OA\Property(property="user", type="string", readOnly="true", description="Associated user name (only in index/list view)", example="John Doe")
 * )
 *
 * @OA\Tag(
 * name="tasks",
 * description="Operations about tasks"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     * path="/tasks",
     * tags={"tasks"},
     * summary="Get all tasks with user names",
     * description="Retrieves a list of all tasks, including the name of the assigned user.",
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Task"))
     * )
     * )
     * )
     */
    //metodo para obtener todas las tareas
    public function index(){

        //SELECT t.*, u.name FROM tasks as t inner join users as u on u.id = t.user_id;
        $tasks = Task::join('users as u','u.id','=','tasks.user_id')->select('tasks.id','tasks.title','tasks.description','tasks.status','tasks.priority','tasks.due_date','tasks.user_id', 'u.name as user')->get();

        return response()->json(["data" => $tasks], 200);
    }

    //registrando una tarea (enviar datos)
    /**
     * @OA\Post(
     * path="/tasks",
     * tags={"tasks"},
     * summary="Create a new task",
     * description="Creates a new task in the system.",
     * @OA\RequestBody(
     * required=true,
     * description="Task data for creation",
     * @OA\JsonContent(
     * required={"title", "status", "priority", "due_date", "user_id"},
     * @OA\Property(property="title", type="string", example="Fix critical bug"),
     * @OA\Property(property="description", type="string", example="The production database is failing."),
     * @OA\Property(property="status", type="string", enum={"pendiente", "en proceso", "completada"}, example="pendiente"),
     * @OA\Property(property="priority", type="string", enum={"baja", "media", "alta"}, example="alta"),
     * @OA\Property(property="due_date", type="string", format="date", example="2025-11-15"),
     * @OA\Property(property="user_id", type="integer", example=10)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Task created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Task created successfully")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
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
    /**
     * @OA\Get(
     * path="/tasks/{taskId}",
     * tags={"tasks"},
     * summary="Get a specific task by ID",
     * description="Returns a single task object based on the provided ID.",
     * @OA\Parameter(
     * name="taskId",
     * in="path",
     * required=true,
     * description="ID of the task to retrieve",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error (e.g., Task ID not found)"
     * )
     * )
     */
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
    /**
     * @OA\Get(
     * path="/tasks/filter",
     * tags={"tasks"},
     * summary="Filter tasks by status or priority",
     * description="Returns a list of tasks filtered by optional status and/or priority query parameters.",
     * @OA\Parameter(
     * name="status",
     * in="query",
     * required=false,
     * description="Filter by task status",
     * @OA\Schema(type="string", enum={"pendiente", "en proceso", "completada"})
     * ),
     * @OA\Parameter(
     * name="priority",
     * in="query",
     * required=false,
     * description="Filter by task priority",
     * @OA\Schema(type="string", enum={"baja", "media", "alta"})
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     * ),
     * @OA\Response(
     * response=204,
     * description="No tasks found matching the criteria"
     * ),
     * @OA\Response(
     * response=422,
     * description="Invalid filter parameter"
     * )
     * )
     */
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


    /**
     * @OA\Patch(
     * path="/tasks/{taskId}",
     * tags={"tasks"},
     * summary="Update an existing task",
     * description="Updates an existing task by ID. Requires authentication.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="taskId",
     * in="path",
     * required=true,
     * description="ID of the task to update",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Fields to update (partial update supported)",
     * @OA\JsonContent(
     * @OA\Property(property="title", type="string", example="Fix critical bug (completed)"),
     * @OA\Property(property="status", type="string", enum={"pendiente", "en proceso", "completada"}, example="completada")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Task updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Task updated successfully")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized (Missing or invalid token)"
     * )
     * )
     */
    public function update(UpdateTaskRequest $request, $taskId){
        //encontrar la tarea en base el id
        $task = Task::find($taskId);
        $task->update($request->all());
        return response()->json(["message" => "Task updated successfully"], 200);
    }


    //devolver cuantos dias falta o si se paso del limite de la fecha
    /**
     * @OA\Get(
     * path="/tasks/remaining-days/{taskId}",
     * tags={"tasks"},
     * summary="Calculate remaining days until due date",
     * description="Calculates the difference in days between today and the task's due date. Requires authentication.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="taskId",
     * in="path",
     * required=true,
     * description="ID of the task",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="task_id", type="integer", example=1),
     * @OA\Property(property="title", type="string", example="Implement Task API"),
     * @OA\Property(property="due_date", type="string", format="date", example="2025-10-30"),
     * @OA\Property(property="remaining_days", type="integer", description="Days remaining (negative if overdue)", example=15),
     * @OA\Property(property="detail", type="string", example="Aun estas a tiempo")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized (Missing or invalid token)"
     * )
     * )
     */
    public function remaininDays($taskId){
        //obtener la tarea
        $task = Task::find($taskId); //objeto
        //obteniendo la fecha actual
        $today = Carbon::today();
        //clave fecha_limite 
        $dueDate = Carbon::parse($task->due_date); //convierte a objeto de tipo Carbon
        //obtener los dias de diferencia entre fechas (aceptamos negativos)
        $diffDays = $today->diffInDays($dueDate, false); 

        return response()->json([
            'task_id' => $task->id,
            'title' => $task->title,
            'due_date' => $task->due_date,
            'remaining_days' => $diffDays,
            'detail' => $diffDays > 0 ? "Aun estas a tiempo" : "La tarea ya vencio!"
        ], 200);

    }
}
