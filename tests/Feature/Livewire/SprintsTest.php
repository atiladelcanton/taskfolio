<?php

use App\Livewire\Sprints;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Sprints::class)
        ->assertStatus(200);
});
