<?php

declare(strict_types=1);

use App\Livewire\App\Domain\Board\BoardComponent;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(BoardComponent::class)
        ->assertStatus(200);
});
