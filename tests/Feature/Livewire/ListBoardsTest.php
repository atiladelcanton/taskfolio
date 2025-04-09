<?php

use App\Livewire\ListBoards;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ListBoards::class)
        ->assertStatus(200);
});
