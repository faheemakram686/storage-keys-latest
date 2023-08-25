<?php
namespace App\Repo\Interfaces;

interface EmailTemplateInterface{
    public function saveEmailTemplate($request);

    public function getAllEmailTemplate();
    public function deleteEmailTemplate($id);
    public function editEmailTemplate($id);
    public function updateEmailTemplate($request);


}
