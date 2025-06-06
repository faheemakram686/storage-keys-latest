<?php

namespace App\Providers;

use App\Models\Core\Auth\User;
use Closure;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    //protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/admin';
//    public const CUSTOMER_HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        // Register route model bindings
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

         Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/auth.php'));
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/ui.php'));
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/customer.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
        Route::prefix('customer-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/customer_api.php'));
    }
}
