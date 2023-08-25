<?php
namespace App\Repo\Interfaces;

interface TaskInterface{
    public function saveTask($request);
    public function getAllTasks();
    public function deleteTask($id);
    public function editTask($id);
    public function updateTask($request);
    public function getRealtedTask($id);

    public function getRelatedTasks($id);

}
