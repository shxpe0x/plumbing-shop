<?php

namespace App\Providers;

use Carbon\Carbon;
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
        $this->registerCarbonMacros();
    }

    /**
     * Регистрирует макросы Carbon для отображения дат в локальном
     * российском формате (Req 26.3).
     *
     * Макросы регистрируются на базовом классе Carbon\Carbon, поэтому
     * доступны и в \Illuminate\Support\Carbon, который наследуется от него.
     */
    private function registerCarbonMacros(): void
    {
        if (! Carbon::hasMacro('toRu')) {
            Carbon::macro('toRu', function () {
                /** @var Carbon $this */
                return $this->format('d.m.Y');
            });
        }

        if (! Carbon::hasMacro('toRuDateTime')) {
            Carbon::macro('toRuDateTime', function () {
                /** @var Carbon $this */
                return $this->format('d.m.Y H:i');
            });
        }
    }
}
