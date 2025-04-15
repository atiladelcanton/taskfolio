<?php

use App\Livewire\Teams\Team;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Team::class)
        ->assertStatus(200);
});
