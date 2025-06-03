<?php

namespace App\Providers;

use App\Repo\AddonClass;
use App\Repo\AdminDashboardClass;
use App\Repo\AppSettingsClass;
use App\Repo\AttachmentClass;
use App\Repo\BlogClass;
use App\Repo\CityClass;
use App\Repo\ContactClass;
use App\Repo\ContractClass;
use App\Repo\ContractTemplateClass;
use App\Repo\CountryClass;
use App\Repo\CouponClass;
use App\Repo\CustomerClass;
use App\Repo\CustomerDashboardClass;
use App\Repo\EmailTemplateClass;
use App\Repo\EstimateClass;
use App\Repo\GoogleServiceGmail;
use App\Repo\InsuranceClass;
use App\Repo\Interfaces\AddonInterface;
use App\Repo\Interfaces\AdminDashboardInterface;
use App\Repo\Interfaces\AppSettingsInterface;
use App\Repo\Interfaces\AttachmentInterface;
use App\Repo\Interfaces\BlogInterface;
use App\Repo\Interfaces\CityInterface;
use App\Repo\Interfaces\ContactInterface;
use App\Repo\Interfaces\ContractInterface;
use App\Repo\Interfaces\ContractTemplateInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\CouponInterFace;
use App\Repo\Interfaces\CustomerDashboardInterface;
use App\Repo\Interfaces\CustomerInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\EstimateInterface;
use App\Repo\Interfaces\GoogleServiceGmailInterface;
use App\Repo\Interfaces\InsuranceInterface;
use App\Repo\Interfaces\InvoiceInterface;
use App\Repo\Interfaces\LeadInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\LocationInterface;
use App\Repo\Interfaces\MeasurementUnitInterface;
use App\Repo\Interfaces\MoveInInterface;
use App\Repo\Interfaces\MoveInRequestInterface;
use App\Repo\Interfaces\MoveOutInterface;
use App\Repo\Interfaces\NoteInterface;
use App\Repo\Interfaces\NotificationInterface;
use App\Repo\Interfaces\OrderInterface;
use App\Repo\Interfaces\PaymentInterface;
use App\Repo\Interfaces\ProductInterface;
use App\Repo\Interfaces\ReminderInterface;
use App\Repo\Interfaces\ReportInterface;
use App\Repo\Interfaces\RequireDocumentInterface;
use App\Repo\Interfaces\RoleInterface;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\StorageUnitInterface;
use App\Repo\Interfaces\StorageUnitLevelInterface;
use App\Repo\Interfaces\StorageUnitSizeInterface;
use App\Repo\Interfaces\TaskInterface;
use App\Repo\Interfaces\TermLengthInterface;
use App\Repo\Interfaces\UserInterface;
use App\Repo\Interfaces\WarehouseInterface;
use App\Repo\InvoiceClass;
use App\Repo\LeadClass;
use App\Repo\LeadSourceClass;
use App\Repo\LeadStatusClass;
use App\Repo\LocationClass;
use App\Repo\MeasurementUnitClass;
use App\Repo\MoveInClass;
use App\Repo\MoveInRequestClass;
use App\Repo\MoveOutClass;
use App\Repo\NoteClass;
use App\Repo\NotificationClass;
use App\Repo\OrderClass;
use App\Repo\PaymentClass;
use App\Repo\ProductClass;
use App\Repo\ReminderClass;
use App\Repo\ReportClass;
use App\Repo\RequireDocumentClass;
use App\Repo\RoleClass;
use App\Repo\StorageTypeClass;
use App\Repo\StorageUnitClass;
use App\Repo\StorageUnitLevelClass;
use App\Repo\StorageUnitSizeClass;
use App\Repo\TaskClass;
use App\Repo\TermLengthClass;
use App\Repo\UserClass;
use App\Repo\WarehouseClass;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use PhpCsFixer\Console\Report\ListSetsReport\ReporterInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\DateTimeType;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->environment('production') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);

        }
        // Ensure you're using Doctrine DBAL
        if (class_exists(Type::class) && !Type::hasType('timestamp')) {
            Type::addType('timestamp', DateTimeType::class);
        }

        // Optional: Tell your platform how to map 'timestamp'
        DB::getDoctrineConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('timestamp', 'datetime');
        $this->app->bind(CountryInterface::class,CountryClass::class);
        $this->app->bind(CityInterface::class,CityClass::class);
        $this->app->bind(AddonInterface::class,AddonClass::class);
        $this->app->bind(WarehouseInterface::class,WarehouseClass::class);
        $this->app->bind(LocationInterface::class,LocationClass::class);
        $this->app->bind(CouponInterFace::class,CouponClass::class);
        $this->app->bind(StorageTypeInterface::class,StorageTypeClass::class);
        $this->app->bind(StorageUnitInterface::class,StorageUnitClass::class);
        $this->app->bind(InsuranceInterface::class,InsuranceClass::class);
        $this->app->bind(ProductInterface::class,ProductClass::class);
        $this->app->bind(MeasurementUnitInterface::class,MeasurementUnitClass::class);
        $this->app->bind(UserInterface::class,UserClass::class);
        $this->app->bind(BlogInterface::class,BlogClass::class);
        $this->app->bind(StorageUnitLevelInterface::class,StorageUnitLevelClass::class);
        $this->app->bind(StorageUnitSizeInterface::class,StorageUnitSizeClass::class);
        $this->app->bind(LeadInterface::class,LeadClass::class);
        $this->app->bind(RoleInterface::class,RoleClass::class);
        $this->app->bind(EstimateInterface::class,EstimateClass::class);
        $this->app->bind(LeadStatusInterface::class,LeadStatusClass::class);
        $this->app->bind(LeadSourceInterface::class,LeadSourceClass::class);
        $this->app->bind(EmailTemplateInterface::class,EmailTemplateClass::class);
        $this->app->bind(CustomerInterface::class,CustomerClass::class);
        $this->app->bind(ContactInterface::class,ContactClass::class);
        $this->app->bind(TaskInterface::class,TaskClass::class);
        $this->app->bind(RequireDocumentInterface::class,RequireDocumentClass::class);
        $this->app->bind(AttachmentInterface::class,AttachmentClass::class);
        $this->app->bind(ContractInterface::class,ContractClass::class);
        $this->app->bind(ReminderInterface::class,ReminderClass::class);
        $this->app->bind(TermLengthInterface::class,TermLengthClass::class);
        $this->app->bind(ContractTemplateInterface::class,ContractTemplateClass::class);
        $this->app->bind(InvoiceInterface::class,InvoiceClass::class);
        $this->app->bind(MoveInRequestInterface::class,MoveInRequestClass::class);
        $this->app->bind(MoveInInterface::class,MoveInClass::class);
        $this->app->bind(PaymentInterface::class,PaymentClass::class);
        $this->app->bind(MoveOutInterface::class,MoveOutClass::class);
        $this->app->bind(AdminDashboardInterface::class,AdminDashboardClass::class);
        $this->app->bind(NotificationInterface::class,NotificationClass::class);
        $this->app->bind(AppSettingsInterface::class,AppSettingsClass::class);
        $this->app->bind(NoteInterface::class,NoteClass::class);
        $this->app->bind(OrderInterface::class,OrderClass::class);
        $this->app->bind(CustomerDashboardInterface::class,CustomerDashboardClass::class);
        $this->app->bind(GoogleServiceGmailInterface::class,GoogleServiceGmail::class);
        $this->app->bind(ReportInterface::class,ReportClass::class);
    }


    public function boot()
    {
        Builder::macro('whereRelationIn', function ($relation, $column, $array) {
            $this->whereHas(
                $relation, function ($q) use ($column, $array) {
                return $q->whereIn($column, $array);
            }
            );
        });
        // $this->app->bind('path.public', function() {
        //     return base_path().'/../public_html';
        // });

        setlocale(LC_TIME, config('app.locale_php'));

        Carbon::setLocale(config('app.locale'));


        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }
    }
}
