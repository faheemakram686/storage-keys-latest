<?php
namespace App\Repo\Interfaces;

interface NotificationInterface{
    public function getAllNotifications();
    public function markAsRead($id);

    public function unreadNotifications();
    public function markAllAsRead();


}
