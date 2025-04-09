<?php

use App\Livewire\App\Domain\Board\BoardComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(BoardComponent::class)
        ->assertStatus(200);
});
