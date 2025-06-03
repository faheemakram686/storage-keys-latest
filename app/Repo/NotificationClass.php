<?php
namespace App\Repo;
use App\Models\Country;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\NotificationInterface;
use Illuminate\Http\Request;
use Auth;

class NotificationClass implements NotificationInterface {


    public function getAllNotifications()
    {

    }

    public function markAsRead($id)
    {

    }

    public function unreadNotifications()
    {
        $notifications = Auth::user()->unreadnotifications;
        return $notifications ;
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return 1;
    }
}
