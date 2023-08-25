<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Task;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\TaskInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TaskClass implements TaskInterface {



    public function saveTask($request)
    {
         $task =new Task();
        $task->subject = $request->subject;
        $task->start_date = $request->start_date;
        $task->due_date = $request->due_date;
        $task->related_to = $request->related_to;
        $task->related_id = $request->related_id;
        $task->assignee =  $request->assignee;
        $task->follower = $request->follower;
        $task->description = $request->description;
        $task->priority = $request->priority;
        $task->status=$request->status;
        if($task->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllTasks()
    {
        $qry=Task::with('assignee');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteTask($id)
    {
        $country=Task::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editTask($id)
    {
      return $task = Task::find($id);
    }

    public function updateTask($request)
    {
        $task = Task::find($request->id);
        $task->subject = $request->subject;
        $task->start_date = $request->start_date;
        $task->due_date = $request->due_date;
        $task->assignee =  $request->assignee;
        $task->follower = $request->follower;
        $task->description = $request->description;
        $task->priority = $request->priority;
        $task->status=$request->status;
        $task->save();
        return 1;
    }

    public function getRealtedTask($id)
    {

    }

    public function getRelatedTasks($request)
    {
        $qry=Task::with('assignee');
        $qry=$qry->where('related_to',$request->related_to);
        $qry=$qry->where('related_id',$request->related_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
}
