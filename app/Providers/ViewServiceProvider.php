<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\NavigationComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share with all views or limit to specific ones
        View::composer('*', NavigationComposer::class);
    }

    public function register()
    {
        //
    }
}
