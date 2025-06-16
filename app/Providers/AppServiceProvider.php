<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\Usuarios;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    Livewire::component('usuarios', Usuarios::class);
    }
}
