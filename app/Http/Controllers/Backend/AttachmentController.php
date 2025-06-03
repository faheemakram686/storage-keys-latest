<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\AttachmentInterface;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    private $attachment;

    public function __construct(AttachmentInterface $attachment)
    {
        $this->attachment = $attachment;
    }

    public function getRelatedAttachment(Request $request)
    {

       return  $this->attachment->getRelatedAttachment($request);
    }

    public function saveCommonAttach(Request $request)
    {
        return $this->attachment->saveCommonAttachment($request);
    }




}
