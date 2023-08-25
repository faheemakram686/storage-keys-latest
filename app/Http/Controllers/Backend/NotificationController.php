<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    private $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }


    public function index()
    {
        $data = $this->notification->unreadNotifications();
        return view('backend.notification.index')->with(compact('data'));
    }
    public function getAllUnreadNotifications()
    {
        $data = $this->notification->unreadNotifications();
        return $data;
    }

    public function markAsAllRead()
    {
        $this->notification->markAllAsRead();
        return redirect()->back();
    }
}
