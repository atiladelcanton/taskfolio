<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Task extends Component
{
    public string $content = '';
    public string $type = 'description';

    #[On('contentUpdated')]
    public function updateContent($value)
    {
        $this->content = $value;

        // Log para debug (remova depois)
        logger('Content updated: ' . $value);
    }

    public function render()
    {
        return view('livewire.task');
    }
}
