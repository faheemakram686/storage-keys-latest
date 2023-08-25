<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Repo\Interfaces\ReminderInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    private $reminder;
    private $user;

    public function __construct(ReminderInterface $reminder)
    {
        $this->reminder = $reminder;
        $this->user = new UserClass();
    }

    public function saveReminder(Request $request)
    {
        return $this->reminder->saveReminder($request);
    }
    public function getRelatedReminders(Request $request)
    {
        return $res = $this->reminder->getRelatedReminder($request);

    }

    public function editReminder(Request $request)
    {

        $data['users']= $this->user->getUser();
        $data['reminder']= $this->reminder->editReminder($request->id);
       return $data;

    }
    public function updateReminder(Request $request)
    {
        $res = $this->reminder->updateReminder($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
    public function deleteReminder(Request $request)
    {
        $this->reminder->deleteReminder($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }
}

