<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\Settings\{Appearance, Password, Profile};
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');


Route::middleware('guest')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
