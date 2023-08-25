<?php
namespace App\Repo\Interfaces;

interface LeadStatusInterface{
    public function saveLeadStatus($request);

    public function getAllLeadStatus();
    public function deleteLeadStatus($id);
    public function editLeadStatus($id);
    public function updateLeadStatus($request);


}
