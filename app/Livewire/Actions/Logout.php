<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\{Auth, Session};

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
