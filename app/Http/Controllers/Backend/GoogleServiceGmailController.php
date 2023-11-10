<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\GoogleServiceGmailInterface;
use Illuminate\Http\Request;

class GoogleServiceGmailController extends Controller
{
    private $gmail;

    public function __construct(GoogleServiceGmailInterface $gmail)
    {

        $this->gmail = $gmail;
    }

    public function getAllGmails()
    {

        return $this->gmail->getGmails();
    }



}
