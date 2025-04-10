<?php

declare(strict_types=1);

namespace App\Livewire\Sprints;

use Illuminate\Contracts\View\Factory;
use Livewire\Component;

class SprintComponent extends Component
{
    public function render(): Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.sprints.sprint-component');
    }
}
