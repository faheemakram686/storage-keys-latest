<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\GoogleServiceGmailInterface;
use Carbon\Carbon;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Attachment;
use Dacastro4\LaravelGmail\Services\Message\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    private $mail;
    public function __construct(GoogleServiceGmailInterface $email)
    {
        $this->mail = $email;
    }
    function gmailBodyDecode($data) {
        $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
        return($data);
    }


        public function index()
    {
        $data['emails'] = collect([]);

        if (LaravelGmail::check()) {
            $messages = LaravelGmail::message()->in('inbox')->preload()->all();

            foreach ($messages as $message) {
                // Skip invalid or unexpected message types
                if (!($message instanceof \Dacastro4\LaravelGmail\Services\Message\Mail)) {
                    continue;
                }

                $id = $message->getId();
                $body = $message->getHtmlBody();
                $subject = $message->getSubject();
                $from = $message->getFrom();
                $date = $message->getDate();

                // If date is empty or not parsable, skip this message
                if (!$date) {
                    continue;
                }

                $customDate = \Carbon\Carbon::parse($date);

                $data['emails']->push([
                    'id' => $id,
                    'body' => $body,
                    'subject' => $subject,
                    'from' => $from,
                    'date' => $customDate,
                ]);
            }
        }

        return view('backend.email.index')->with(compact('data'));
    }

    public function getEmails()
    {
        if(LaravelGmail::check())
        {
            $messages = LaravelGmail::message()->in( $box = 'inbox' )->preload()->all();
            $data['emails'] = collect([]);
            foreach ( $messages as $message ) {
                $id = $message->getId();
                $body = $message->getHtmlBody();
                $subject = $message->getSubject();
                $from = $message->getFrom();
                $date = $message->getDate();
                $customDate = Carbon::parse($date);

                $array = array(
                    'body' => $body,
                    'subject' =>$subject,
                    'from' =>$from,
                    'date' =>$customDate,
                );
                $data['emails']->push($array);
            }
            return $array;

        }
    }

    public function getEmailDetail($id)
    {
        if(LaravelGmail::check())
        {
            $messages = LaravelGmail::message()->in( $box = 'inbox' )->preload()->get($id);
                $id = $messages->getId();
                $body = $messages->getHtmlBody();
                $subject = $messages->getSubject();
                $from = $messages->getFrom();
                $date = $messages->getDate();;
                $customDate = Carbon::parse($date);
//                $attachment = $messages->load ();
//                dd($attachment);
//                if($attachment)
//                {
//                    $attach_files = $attachment->getData();
//                    dd($attach_files);
//                }

            $data = array(
                    'id' => $id,
                    'body' => $body,
                    'subject' =>$subject,
                    'from' =>$from,
                    'date' =>$customDate,
                );
            return view('backend.email.email-detail')->with(compact('data'));
        }
    }

    public function sendEmail(Request $request)
    {
        try {
            $jsonString = $request->to;
            $data = json_decode($jsonString, true);
            $jsonString = $request->cc;
            $datacc = json_decode($jsonString, true);
            $jsonString = $request->bcc;
            $databcc = json_decode($jsonString, true);
            if ($data) {
                $emailAddresses = array();
                foreach ($data as $item) {
                    if (isset($item['value']) && filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                        $emailAddresses[] = $item['value'];
                        $mail = new Mail();
                        $mail->from(LaravelGmail::user());
                        $mail->to($item['value']);
                        if ($datacc) {
                            foreach ($datacc as $itemcc) {
                                if (isset($itemcc['value']) && filter_var($itemcc['value'], FILTER_VALIDATE_EMAIL)) {
                                    $mail->cc($itemcc['value']);
                                }
                            }
                        }
                        if ($databcc) {
                            foreach ($databcc as $itembcc) {
                                if (isset($itembcc['value']) && filter_var($itembcc['value'], FILTER_VALIDATE_EMAIL)) {
                                    $mail->bcc($itembcc['value']);
                                }
                            }
                        }
                        $mail->subject($request->subject);
                        if($request->hasFile('attachment'))
                        {
                            $uniqueid = uniqid();
                            $original_name = $request->file('attachment')->getClientOriginalName();
                            $name =  $uniqueid . '_' . $original_name;
                            $path = $request->file('attachment')->storeAs('public/uploads/attachments/', $name);
                            $mail->attach(public_path('storage/uploads/attachments/'.$name));
                        }
                        $mail->message($request->messege);
                        $mail->send();
                    }
                }
            } else {
                return response()->json(['errors' => "Error decoding JSON."], 201);
            }

            return response()->json(['success' => 'Email sent successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 201);
        }

    }


}
