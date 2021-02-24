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
            // dd($route);
            $route_as       = explode(".", $route->action['as']);
            $route_as_name  = explode(".", $route->action['as'])[0];
            $title          = ucfirst($route_as[0]);
            $subTitle       = "";
            if (isset($route_as[1])) {
                $subTitle   = ucfirst(($route_as[1] == 'index' ? 'list' : $route_as[1]));
            }
            $route_prefix   = ucfirst(str_replace("/", "", $route->action['prefix']));
            $breadcrumbs    = [$route_prefix, $title, $subTitle];
            $view->with([
                'route'         =>  $route,
                'route_as'      =>  $route_as,
                'route_as_name' =>  $route_as_name,
                'title'         =>  $title,
                'subTitle'      =>  $subTitle,
                'route_prefix'  =>  $route_prefix,
                'breadcrumbs'   =>  $breadcrumbs
            ]);
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
