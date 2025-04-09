<?php

use App\Livewire\Sprints\SprintComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SprintComponent::class)
        ->assertStatus(200);
});
