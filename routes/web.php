<?php

declare(strict_types=1);

use App\Livewire\Settings\{Appearance, Password, Profile};
use App\Livewire\Sprints;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/projects', App\Livewire\Projects\Project::class)->name('projects.index');
    Route::get('/teams', App\Livewire\Teams\Team::class)->name('team');
    Route::get('/sprints/{project_code}', Sprints\Sprint::class)->name('sprints.index');
});

require __DIR__.'/auth.php';
Route::get('/register-invitation', App\Livewire\Invitations\RegisterInvitation::class)->name('register-invitation');
