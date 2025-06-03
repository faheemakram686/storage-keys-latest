<?php
namespace App\Repo\Interfaces;

interface MoveInRequestInterface{
    public function saveMoveInRequest($request);
    public function saveMoveInRequestApi($request);

    public function getAllMoveInRequest();
    public function getCustomerMoveInRequest($id);
    public function deleteMoveInRequest($id);
    public function editMoveInRequest($id);
    public function genrateBarcode($request);
    public function updateMoveInRequest($request);


}
