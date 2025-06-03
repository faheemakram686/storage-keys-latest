<?php

namespace App\Repo;

use App\Repo\Interfaces\GoogleServiceGmailInterface;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Nette\Utils\Strings;
use function phpseclib3\Common\Functions\Strings;

class GoogleServiceGmail implements GoogleServiceGmailInterface
{


    public function getGmails()
    {
        $client = new Client();
        $client->setApplicationName(env('GOOGLE_PROJECT_ID'));
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI', 'APP_URL/login/google/callback'));
        $client->setScopes([Gmail::GMAIL_READONLY, Gmail::GMAIL_COMPOSE]);

//       / $authUrl = $client->createAuthUrl();
//        $authCode = urldecode($request->input('auth_code'));
//        return $authUrl;
//        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
// Set the access token obtained from the OAuth 2.0 flow

        if(!$client->getAccessToken()){
            $accessToken = env('GOOGLE_ACCESS_TOKEN');
            $client->setAccessToken($accessToken);
//            $service = new Gmail($client);
        }else{
            $refresh =$client->getRefreshToken();
            $tok = $client->refreshToken($refresh);
            $accessToken2 = $client->fetchAccessTokenWithRefreshToken($tok);
            $client->setAccessToken($accessToken2);
        }


// Create a new Gmail service object
        $service = new Gmail($client);
        $strSubject = 'Test mail using GMail API Faheem ' . date('M d, Y h:i:s A');
//        $this->sendEmail('faheemakram686@gmail.com','faheemakrambwn686@gmail.com',$strSubject,'this is tesing email messege faheem',$service);
//        $this->getEmails($client);
        $this->getEmails($service);
    }
   public function sendEmail($sender, $to, $strSubject, $messageText,$service) {
        try {
        $strRawMessage = "From: myAddress<$sender.>\r\n";
        $strRawMessage .= "To: toAddress <$to>\r\n";
        $strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $strRawMessage .= "$messageText\r\n";
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new \Google_Service_Gmail_Message();
        $msg->setRaw($mime);
            $message =   $service->users_messages->send("me", $msg);
            print 'Message with ID: ' . $message->getId() . ' sent.';
            return $message;
        } catch (\Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
    }

    public function createDraft($service, $user, $message) {
        $draft = new \Google_Service_Gmail_Draft();
        $draft->setMessage($message);
        try {
            $draft = $service->users_drafts->create($user, $draft);
            print 'Draft ID: ' . $draft->getId();
        } catch (\Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
        return $draft;
    }

    public function getEmails($service)
    {
        $results = $service->users_messages->listUsersMessages('me');
        $data['emails'] = collect([]);
        foreach ($results->getMessages() as $message) {
            $messageId = $message->getId();
            $optParamsGet2['format'] = 'full';
            $message = $service->users_messages->get('me', $messageId, $optParamsGet2);
            $snippet = $message->getSnippet();
            $data['emails']->push($snippet);
            print_r($data);
            break;
        }
        return $data;
    }
    function gmailBodyDecode($data) {
        $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
        return($data);
    }
    function decodeBody($body) {
        $rawData = $body;
        $sanitizedData = strtr($rawData,'-_', '+/');
        $decodedMessage = base64_decode($sanitizedData);
        if(!$decodedMessage){
            $decodedMessage = FALSE;
        }
        return $decodedMessage;
    }

    public function getEmails2($client)
    {

        $gmail = new \Google_Service_Gmail($client);

        $list = $gmail->users_messages->listUsersMessages('me', ['maxResults' => 1000]);

        try{
            while ($list->getMessages() != null) {

                foreach ($list->getMessages() as $mlist) {

                    $message_id = $mlist->id;
                    $optParamsGet2['format'] = 'full';
                    $single_message = $gmail->users_messages->get('me', $message_id, $optParamsGet2);
                    $payload = $single_message->getPayload();
                    $parts = $payload->getParts();
                    // With no attachment, the payload might be directly in the body, encoded.
                    $body = $payload->getBody();
                    $FOUND_BODY = FALSE;
                    // If we didn't find a body, let's look for the parts
                    if(!$FOUND_BODY) {
                        foreach ($parts  as $part) {
                            if($part['parts'] && !$FOUND_BODY) {
                                foreach ($part['parts'] as $p) {
                                    if($p['parts'] && count($p['parts']) > 0){
                                        foreach ($p['parts'] as $y) {
                                            if(($y['mimeType'] === 'text/html') && $y['body']) {
                                                $FOUND_BODY = $this->decodeBody($y['body']->data);
                                                break;
                                            }
                                        }
                                    } else if(($p['mimeType'] === 'text/html') && $p['body']) {
                                        $FOUND_BODY = $this->decodeBody($p['body']->data);
                                        break;
                                    }
                                }
                            }
                            if($FOUND_BODY) {
                                break;
                            }
                        }
                    }
                    // let's save all the images linked to the mail's body:
                    if($FOUND_BODY && count($parts) > 1){
                        $images_linked = array();
                        foreach ($parts  as $part) {
                            if($part['filename']){
                                array_push($images_linked, $part);
                            } else{
                                if($part['parts']) {
                                    foreach ($part['parts'] as $p) {
                                        if($p['parts'] && count($p['parts']) > 0){
                                            foreach ($p['parts'] as $y) {
                                                if(($y['mimeType'] === 'text/html') && $y['body']) {
                                                    array_push($images_linked, $y);
                                                }
                                            }
                                        } else if(($p['mimeType'] !== 'text/html') && $p['body']) {
                                            array_push($images_linked, $p);
                                        }
                                    }
                                }
                            }
                        }
//                        // special case for the wdcid...
                        preg_match_all('/wdcid(.*)"/Uims', $FOUND_BODY, $wdmatches);
                        if(count($wdmatches)) {
                            $z = 0;
                            foreach($wdmatches[0] as $match) {
                                $z++;
                                if($z > 9){
                                    $FOUND_BODY = str_replace($match, 'image0' . $z . '@', $FOUND_BODY);
                                } else {
                                    $FOUND_BODY = str_replace($match, 'image00' . $z . '@', $FOUND_BODY);
                                }
                            }
                        }
                        preg_match_all('/src="cid:(.*)"/Uims', $FOUND_BODY, $matches);
                        if(count($matches)) {
                            $search = array();
                            $replace = array();
                            // let's trasnform the CIDs as base64 attachements
                            foreach($matches[1] as $match) {
                                foreach($images_linked as $img_linked) {
                                    foreach($img_linked['headers'] as $img_lnk) {
                                        if( $img_lnk['name'] === 'Content-ID' || $img_lnk['name'] === 'Content-Id' || $img_lnk['name'] === 'X-Attachment-Id'){
                                            if ($match === str_replace('>', '', str_replace('<', '', $img_lnk->value))
                                                || explode("@", $match)[0] === explode(".", $img_linked->filename)[0]
                                                || explode("@", $match)[0] === $img_linked->filename){
                                                $search = "src=\"cid:$match\"";
                                                $mimetype = $img_linked->mimeType;
                                                $attachment = $gmail->users_messages_attachments->get('me', $mlist->id, $img_linked['body']->attachmentId);
                                                $data64 = strtr($attachment->getData(), array('-' => '+', '_' => '/'));
                                                $replace = "src=\"data:" . $mimetype . ";base64," . $data64 . "\"";
                                                $FOUND_BODY = str_replace($search, $replace, $FOUND_BODY);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // If we didn't find the body in the last parts,
                    // let's loop for the first parts (text-html only)
                    if(!$FOUND_BODY) {
                        foreach ($parts  as $part) {
                            if($part['body'] && $part['mimeType'] === 'text/html') {
                                $FOUND_BODY = $this->decodeBody($part['body']->data);
                                break;
                            }
                        }
                    }
                    // With no attachment, the payload might be directly in the body, encoded.
                    if(!$FOUND_BODY) {
                        $FOUND_BODY = $this->decodeBody($body['data']);
                    }
                    // Last try: if we didn't find the body in the last parts,
                    // let's loop for the first parts (text-plain only)
                    if(!$FOUND_BODY) {
                        foreach ($parts  as $part) {
                            if($part['body']) {
                                $FOUND_BODY = $this->decodeBody($part['body']->data);
                                break;
                            }
                        }
                    }
                    if(!$FOUND_BODY) {
                        $FOUND_BODY = '(No message)';
                    }
                    // Finally, print the message ID and the body
                    print_r($message_id . ": " . $FOUND_BODY);
                }

                if ($list->getNextPageToken() != null) {
                    $pageToken = $list->getNextPageToken();
                    $list = $gmail->users_messages->listUsersMessages('me', ['pageToken' => $pageToken, 'maxResults' => 1000]);
                } else {
                    break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }



}