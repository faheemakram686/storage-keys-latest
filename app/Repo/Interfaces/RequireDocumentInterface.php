<?php
namespace App\Repo\Interfaces;

interface RequireDocumentInterface{
    public function saveRequireDocument($request);

    public function getAllRequireDocument();
    public function deleteRequireDocument($id);
    public function editRequireDocument($id);
    public function updateRequireDocument($request);


}
