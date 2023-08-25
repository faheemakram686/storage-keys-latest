<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\StorageType;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Notifications\WelcomeNotification;
use App\Repo\AddonClass;
use App\Repo\AppSettingsClass;
use App\Repo\CountryClass;
use App\Repo\LocationClass;
use App\Repo\ProductClass;
use App\Repo\StorageTypeClass;
use App\Repo\StorageUnitClass;
use App\Repo\StorageUnitLevelClass;
use App\Repo\StorageUnitSizeClass;
use App\Repo\TermLengthClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{

    private  $country;
    private $sl;
    private $ss;
    private $st;
    private $su;
    private $addon;
    private $term_length;
    private $product;
    private $appsettings;

    public function __construct()
    {
        $this->country = new CountryClass();
        $this->sl = new StorageUnitLevelClass();
        $this->ss = new StorageUnitSizeClass();
        $this->st = new StorageTypeClass();
        $this->su = new StorageUnitClass();
        $this->addon = new AddonClass();
        $this->term_length = new TermLengthClass();
        $this->product = new ProductClass();
        $this->appsettings = new AppSettingsClass();

    }
    public function index(){

//        $welcome =[
//            'title' =>'Welcome to Storage Keys',
//            'slug' =>'/',
//        ];
//
//        $users = User::get();
//        foreach ($users as $user)
//        {
//            Notification::send($user,new WelcomeNotification($welcome));
//        }
//
//
//        dd('done');
//        return Auth::user();
        return view('ui.pages.landing');
    }
    public function notify()
    {
        if(auth()->user())
        {
            $user  = User::find(2);
            $userauth = auth()->user();
            Notification::send($userauth,new UserNotification($user));
//            notify()->with($user)->send(UserNotification::class);
            dd('done');
        }


    }
    public function storageOption(){
        return view('ui.pages.storage-options');
    }
    public function shop()
    {
        $data['product'] = $this->product->getProductPaginate();
        return view('ui.pages.shop')->with(compact('data'));
    }
    public function productDetails(){
        return view('ui.pages.product-details');
    }
    public function productDetail(Request $request){
      return $this->product->getProductDetail($request->id);
    }
    public function booking(){
        $data['loc'] =  $this->country->getAllCountry();
        $data['sl'] =  $this->sl->getStorageLevel();
        $data['ss'] =  $this->ss->getStorageSize();
        $data['st'] =  $this->st->getStorageType();

        return view('ui.pages.booking')->with(compact('data'));;
    }
    public function blogs(){
        return view('ui.pages.blogs');
    }
    public function aboutUs(){
        return view('ui.pages.about-us');
    }
    public function contactUs(){
        return view('ui.pages.contact-us');
    }
    public function blogDetails(){
        return view('ui.pages.blog-details');
    }
    public function bookingReservation($id)
    {
          $data['addon'] = $this->addon->getStorageUnitAddon();
          $data['su'] = $this->su->leadStorageUnit($id);
          $data['term_length'] =  $this->term_length->getAllTermLength();
          $data['terms_conditions'] =  $this->appsettings->getAppSettings();
        return view('ui.pages.reservation')->with(compact('data'));
    }
}
