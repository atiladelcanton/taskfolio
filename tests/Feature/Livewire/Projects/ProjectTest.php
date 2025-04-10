<?php

declare(strict_types=1);

use App\Livewire\Projects\Project;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Project::class)
        ->assertStatus(200);
});
