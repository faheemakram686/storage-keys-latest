<?php
namespace App\Repo\Interfaces;

interface AttachmentInterface{
    public function saveAttachment($request);
    public function saveCommonAttachment($request);

    public function getAllAttachment();
    public function deleteAttachment($id);
    public function editAttachment($id);
    public function updateAttachment($request);
    public function getAttachment($request);
    public function getRelatedAttachment($request);


}
