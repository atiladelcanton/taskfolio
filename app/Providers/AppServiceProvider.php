<?php

namespace App\Providers;
use App\Domain\Project\Observers\ProjectObserver;
use App\Models\Project;
use App\View\Components\UserAvatar;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Verified;

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
