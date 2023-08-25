<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\TaskInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $task;
    private $user;

    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
        $this->user = new UserClass();
    }

    public function saveTask(Request $request)
    {
        return $res = $this->task->saveTask($request);
    }
    public function getTasks()
    {
        return $res = $this->task->getAllTasks();
    }

    public function getRelatedTasks(Request $request)
    {
        return $res = $this->task->getRelatedTasks($request);
    }

    public function editTask(Request $request)
    {
        $data['task'] = $this->task->editTask($request->id);
        $data['users'] = $this->user->getUser();
        return $data;

    }
    public function updateTask(Request $request)
    {
        $res = $this->task->updateTask($request);
        return response()->json(['success' => 'Record updated successfully'], 200);

    }

    public function deleteTask(Request $request)
    {
        $this->task->deleteTask($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }


}
