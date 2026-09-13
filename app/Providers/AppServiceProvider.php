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

                    // Sidebar dropdowns expect these exact variable names.
                    // Items without a `route` render as disabled labels, so
                    // the placeholders keep fresh installs (no businesses
                    // yet) from hitting missing-parameter route errors.
                    $view->with(
                        'kelolaStokDropdown',
                        $businesses->map(fn ($b) => [
                            'label' => $b->name,
                            'route' => 'admin.manage-stock',
                            'params' => [$b->id],
                        ])->values()->all() ?: [
                            ['label' => 'Belum ada usaha', 'route' => null],
                        ]
                    );
                    $view->with(
                        'businesItems',
                        $businesses->map(fn ($b) => [
                            'label' => $b->name,
                            'route' => 'admin.laporan',
                            'params' => [$b->id],
                        ])->values()->all() ?: [
                            ['label' => 'Belum ada usaha', 'route' => null],
                        ]
                    );
                }
            }
        });
        Blade::component('Components.Ui.Input', 'ui-input');
    }
}
