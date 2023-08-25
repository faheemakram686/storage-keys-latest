<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\EmailTemplateInterface;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{

    private $email_template ;

    public function __construct(EmailTemplateInterface $emailTemplate)
    {
        $this->email_template = $emailTemplate;
    }

    public function index()
    {
        return view('backend.settings.email-templates.index');
    }

    public function saveEmailTemplate(Request $request)
    {
        return $this->email_template->saveEmailTemplate($request);
    }
    public function getEmailTemplate(Request $request)
    {
        return $this->email_template->getAllEmailTemplate();

    }
    public function deleteEmailTemplate(Request $request)
    {
        $this->email_template->deleteEmailTemplate($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editEmailTemplate(Request $request)
    {
        $data['st']=$this->email_template->editEmailTemplate($request->id);
        return view('backend.settings.email-templates.edit')->with(compact('data'));
    }
    public function updateEmailTemplate(Request $request)
    {


        $res=$this->email_template->updateEmailTemplate($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

}
