<?php

namespace App\Filament\Facades;

use App\Filament\Services\UserService;
use Illuminate\Support\Facades\Facade;

class UserServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UserService::class;
    }
}
