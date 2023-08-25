<?php
namespace App\Repo\Interfaces;

interface LeadInterface{
    public function saveLead($request);
    public function changeStatus($request);
    public function changeSource($request);
    public function changeAssignee($request);
    public function saveLeadBackend($request);

    public function getAllLead();
    public function deleteLead($id);
    public function editLead($id);
    public function updateLead($request);


}
