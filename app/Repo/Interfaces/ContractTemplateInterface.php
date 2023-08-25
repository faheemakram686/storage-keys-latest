<?php
namespace App\Repo\Interfaces;

interface ContractTemplateInterface{
    public function saveTemplate($request);

    public function getAllTemplate();
    public function deleteTemplate($id);
    public function editTemplate($id);
    public function updateTemplate($request);
    public function getTemplate($request);
    public function getRelatedTemplate($request);


}
