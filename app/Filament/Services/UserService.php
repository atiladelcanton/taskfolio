<?php

namespace App\Filament\Services;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService
{
    public function createUserCollaborato(array $data): User
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => '123@'.Str::slug($data['name']),
            'is_active' => true,
        ]);
        $role = DB::table('roles')->where('name', '=', 'Colaboradores')->first();

        $user->assignRole([$role->id]);
        $this->sendEmailReminder($user);

        return $user;
    }

    public function setValidatedEmail(Request $request): RedirectResponse
    {

        $user = User::findOrFail($request->id);
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        if (! Auth::loginUsingId($user->id)) {
            abort(403);
        }

        return redirect()->to('/system');
    }

    public function sendEmailReminder(User $user, $toNotify = false): void
    {
        try {
            // this is important!
            $user->notify(new VerifyEmailNotification());
            // my custom logics
            if ($toNotify) {
                Notification::make()
                    ->title('Aviso')
                    ->body('Email di notifica Attivazione Account inviata')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Errore durante la richiesta')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
