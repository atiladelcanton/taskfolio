<?php

namespace App\Actions\Clients;

use App\Models\User;

class UpdateUserClient
{
    public static function make(array $data, int $userId)
    {

        $user = User::find($userId);

        if ($user->email != $data['email']) {
            $user->email = $data['email'];
            $user->save();
            $user->fresh();
        }
        if ($data['password_user'] != null) {
            $user->password = bcrypt($data['password_user']);
            $user->save();
            $user->fresh();
        }
    }
}
