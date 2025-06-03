<?php
namespace App\Repo\Interfaces;

interface TermLengthInterface{
    public function saveTermLength($request);

    public function getAllTermLength();
    public function deleteTermLength($id);
    public function editTermLength($id);
    public function updateTermLength($request);


}
