<?php
namespace App\Repo\Interfaces;

interface MoveOutInterface{
    public function saveMoveOut($request);

    public function getAllMoveOut();
    public function deleteMoveOut($id);
    public function editMoveOut($id);

    public function updateMoveOut($request);


}
