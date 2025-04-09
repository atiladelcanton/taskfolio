<?php

declare(strict_types=1);

use App\Livewire\Projects;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Projects::class)
        ->assertStatus(200);
});
