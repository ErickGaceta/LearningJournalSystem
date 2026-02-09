<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;
use App\Models\Document;
use App\Models\DivisionUnit;
use App\Models\Position;
use Illuminate\Pagination\Paginator;

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
        $this->configureDefaults();
        Paginator::useTailwind();

        // Share recent documents only with specific views (like dashboard, sidebar, etc.)
        View::composer(['dashboard', 'layouts.navigation', 'layouts.sidebar'], function ($view) {
            $view->with('recentDocuments', Document::latest()->take(10)->get());
        });

        // Share divisions and positions with register view
        View::composer('pages.auth.register', function ($view) {
            $view->with([
                'divisions' => DivisionUnit::pluck('division_units', 'id'),
                'positions' => Position::pluck('positions', 'id'),
            ]);
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null
        );
    }
}