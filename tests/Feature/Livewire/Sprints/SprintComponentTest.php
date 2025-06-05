<?php

declare(strict_types=1);

use App\Livewire\Sprints\Sprint;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Sprint::class)
        ->assertStatus(200);
});
