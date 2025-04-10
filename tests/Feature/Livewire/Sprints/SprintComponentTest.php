<?php

declare(strict_types=1);

use App\Livewire\Sprints\SprintComponent;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(SprintComponent::class)
        ->assertStatus(200);
});
