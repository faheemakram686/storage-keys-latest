<?php
namespace App\Repo\Interfaces;

interface EstimateInterface{
    public function saveEstimate($request);

    public function getAllEstimate();
    public function deleteEstimate($id);
    public function editEstimate($id);
    public function updateUpdate($request);


    public function getEstimate($id);
    public function approveEstimate($id);
    public function declineEstimate($request);
    public function getEstimateReqDocs($id);

}
