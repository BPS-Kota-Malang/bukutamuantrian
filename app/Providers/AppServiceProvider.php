<?php

namespace App\Providers;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Resources\Pages\Page;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;


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
        Livewire::component('play-audio-modal', \App\Livewire\PlayAudioModal::class);
    }
}
