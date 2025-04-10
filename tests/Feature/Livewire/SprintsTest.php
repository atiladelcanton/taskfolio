<?php

declare(strict_types=1);

use App\Livewire\Sprints;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Sprints::class)
        ->assertStatus(200);
});
