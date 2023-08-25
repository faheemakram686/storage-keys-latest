<?php
namespace App\Repo\Interfaces;

interface LeadSourceInterface{
    public function saveLeadSource($request);

    public function getAllLeadSource();
    public function deleteLeadSource($id);
    public function editLeadSource($id);
    public function updateLeadSource($request);


}
