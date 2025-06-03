<?php
namespace App\Repo\Interfaces;

interface ReminderInterface{
    public function saveReminder($request);

    public function getAllReminder();
    public function deleteReminder($id);
    public function editReminder($id);
    public function updateReminder($request);
    public function getReminder($request);
    public function getRelatedReminder($request);


}
