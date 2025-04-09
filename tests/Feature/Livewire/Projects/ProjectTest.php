<?php

use App\Livewire\Projects\Project;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Project::class)
        ->assertStatus(200);
});
