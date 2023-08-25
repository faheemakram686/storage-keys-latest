<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\AppSettingsInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{

    private $users;
    private $appsettings;

    public function __construct(AppSettingsInterface $appsettings )
    {
        $this->appsettings = $appsettings;
        $this->users = new UserClass();
    }



    public function index()
    {
        $data['users'] = $this->users->getUser();
        $data['appSettings'] = $this->appsettings->getAppSettings();
        return view('backend.settings.app-settings.index1')->with(compact('data'));
    }

    public function update(Request $request)
    {
//        return $request->all();
        return $this->appsettings->update($request);
    }
    public function getAppsettings()
    {
            $data['appSettings'] = $this->appsettings->getAppSettings();
    }
}
