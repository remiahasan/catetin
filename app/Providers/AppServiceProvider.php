<?php

namespace App\Providers;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                if ($user->role == 'owner') {
                    $businesses = Business::all();
                    // dd($businesses);

                    $view->with('businesses', $businesses);
                }
            }
        });
        Blade::component('Components.Ui.Input', 'ui-input');
    }
}
