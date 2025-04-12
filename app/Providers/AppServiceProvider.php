<?php

namespace App\Providers;
use App\Domain\Project\Models\Project;
use App\Domain\Project\Observers\ProjectObserver;
use App\View\Components\UserAvatar;
use Illuminate\Support\Facades\Blade;
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
        Project::observe(ProjectObserver::class);

        Blade::component('user-avatar', UserAvatar::class);
    }
}
