<?php

declare(strict_types=1);

use App\Livewire\Teams\Team;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Team::class)
        ->assertStatus(200);
});
