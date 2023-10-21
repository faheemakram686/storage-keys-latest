<?php
namespace App\Repo\Interfaces;

interface MoveInInterface{
    public function saveMoveIn($request);

    public function getAllMoveIn();
    public function deleteMoveIn($id);
    public function editMoveIn($id);
    public function genrateBarcode($request);
    public function updateMoveIn($request);
    public function viewMoveInItems($id);


}
