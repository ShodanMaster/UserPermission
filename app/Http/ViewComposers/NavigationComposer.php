<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as LaravelRoute;

class NavigationComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        $routes = [];

        if ($user && method_exists($user, 'routes')) {
            $routes = $user->routes()
                ->orderBy('order')
                ->get()
                ->map(function ($route) {
                    
                    if (!LaravelRoute::has($route->route) && LaravelRoute::has($route->route . '.index')) {
                        $route->route = $route->route . '.index';
                    }
                    return $route;
                });
        }

        $view->with('routes', $routes);
    }
}
