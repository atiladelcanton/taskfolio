<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\Actions;

use App\Domain\User\Actions\CreateUserAction;
use App\Domain\User\Events\UserCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateUserAction;
    }

    public function test_it_creates_a_user_successfully(): void
    {
        Event::fake();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = $this->action->execute($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNotEquals('password123', $user->password); // Password should be hashed
        $this->assertNull($user->email_verified_at);

        Event::assertDispatched(UserCreated::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    public function test_it_throws_validation_exception_for_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $invalidData = [
            'name' => '', // Empty name
            'email' => 'not-an-email', // Invalid email
            'password' => '123', // Too short password
        ];

        $this->action->execute($invalidData);
    }

    public function test_it_throws_exception_when_user_creation_fails(): void
    {
        $this->expectException(ModelNotFoundException::class);

        // Mock User::create to throw an exception
        $this->mock(User::class)
            ->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $this->action->execute($userData);
    }

    public function test_it_creates_user_with_email_verified(): void
    {
        $now = now();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'email_verified_at' => $now,
        ];

        $user = $this->action->execute($userData);

        $this->assertEquals($now->format('Y-m-d H:i:s'), $user->email_verified_at->format('Y-m-d H:i:s'));
    }
}
