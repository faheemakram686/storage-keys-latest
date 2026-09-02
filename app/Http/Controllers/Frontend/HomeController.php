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
use App\Services\InsurancePricingService;
use App\Models\Blog;
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
        return view('ui.pages.landing', [
            'shopProducts' => $this->product->getLandingProducts(),
        ]);
    }
    public function notify()
    {
        if(auth()->user())
        {
            $user  = User::find(2);
            $userauth = auth()->user();
            Notification::send($userauth,new UserNotification($user));
//            notify()->with($user)->send(UserNotification::class);

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
        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->paginate(9);

        return view('ui.pages.blogs', compact('blogs'));
    }
    public function aboutUs(){
        return view('ui.pages.about-us');
    }
    public function contactUs(){
        return view('ui.pages.contact-us');
    }
    public function privacyPolicy(){
        return view('ui.pages.privacy-policy');
    }

    public function securityPolicy(){
        return view('ui.pages.security-policy');
    }

    public function supportPolicy(){
        return view('ui.pages.support-policy');
    }

    public function cookiePolicy(){
        return view('ui.pages.cookie-policy');
    }

    public function termsOfService(){
        return view('ui.pages.terms-of-service');
    }

    public function frequentlyAskedQuestions(){
        return view('ui.pages.frequently-asked-questions');
    }

    public function thankYou()
    {
        return view('ui.pages.thank-you');
    }
    public function blogDetails($slug)
    {
        $blog = Blog::query()
            ->where('is_deleted', 0)
            ->where('slug', $slug)
            ->firstOrFail();

        $recent = Blog::query()
            ->where('is_deleted', 0)
            ->where('id', '!=', $blog->id)
            ->orderBy('id', 'DESC')
            ->limit(4)
            ->get();

        return view('ui.pages.blog-details', compact('blog', 'recent'));
    }
    public function businessStorage(){
        return view('ui.pages.business-storage');
    }
    public function personalStorage(){
        return view('ui.pages.personal-storage');
    }
    public function furnitureStorage(){
        return view('ui.pages.furniture-storage');
    }

    public function boxStorage(){
        return view('ui.pages.box-storage');
    }

    public function applianceStorage(){
        return view('ui.pages.appliance-storage');
    }

    public function residentialStorage(){
        return view('ui.pages.residential-storage');
    }
    public function climateControlledStorage(){
        return view('ui.pages.climate-controlled-storage');
    }
    public function warehouseStorage(){
        return view('ui.pages.warehouse-storage');
    }
    public function movingServices()
    {
        return view('ui.pages.moving-services');
    }
    public function luggageStorage()
    {
        return view('ui.pages.luggage-storage');
    }
    public function carStorage()
    {
        return view('ui.pages.car-storage');
    }
    public function bookingReservation($id)
    {
          $data['addon'] = $this->addon->getStorageUnitAddon();
          $data['su'] = $this->su->leadStorageUnit($id);
          $data['loc'] = $this->country->getAllCountry();
          $data['term_length'] =  $this->term_length->getAllTermLength();
          $data['terms_conditions'] =  $this->appsettings->getAppSettings();
          $data['insurances'] = app(InsurancePricingService::class)->activePackages();
        return view('ui.pages.reservation')->with(compact('data'));
    }
}
