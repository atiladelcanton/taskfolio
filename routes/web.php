<?php

use App\Filament\Services\UserService;

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, UserService $customerService) {
    return $customerService->setValidatedEmail($request);
})->name('verification.verify');
