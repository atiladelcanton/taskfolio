<?php

declare(strict_types=1);
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Event, Notification};

beforeEach(function () {
    Event::fake([Registered::class]);
});

test('novos usuários podem se registrar', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);
});
