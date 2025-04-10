<?php

declare(strict_types=1);

use App\Livewire\ListBoards;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(ListBoards::class)
        ->assertStatus(200);
});
