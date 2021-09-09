<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*', function ($view) {
            // all views will have access to current rout
            $route          = \Route::current();
            if ($route) {
                // dd($route);
                $route_as       = explode(".", $route->action['as']);
                $route_as_name  = explode(".", $route->action['as'])[0];
                $title_first    = ucwords(str_replace('_', ' ', $route_as[0]));
                $subTitle       = "";
                if (isset($route_as[1])) {
                    $subTitle   = ucwords(($route_as[1] == 'index' ? 'list' : str_replace('_', ' ', $route_as[1])));
                }
                $route_prefix   = ucfirst(str_replace("/", "", $route->action['prefix']));
                $breadcrumbs    = [$route_prefix, $title_first, $subTitle];
                $view->with([
                    'route'         =>  $route,
                    'route_as'      =>  $route_as,
                    'route_as_name' =>  $route_as_name,
                    'title_first'   =>  $title_first,
                    'subTitle'      =>  $subTitle,
                    'route_prefix'  =>  $route_prefix,
                    'breadcrumbs'   =>  $breadcrumbs
                ]);
            }
        });
    
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
