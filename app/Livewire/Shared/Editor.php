<?php

namespace App\Livewire\Shared;

use App\Models\User;
use Livewire\Component;

class Editor extends Component
{
    public $content = '';
    public $placeholder = 'Digite aqui...';
    public $showWordCount = false;
    public $maxLength = null;

    // Estados para menções
    public $showMentionsList = false;
    public $mentionQuery = '';
    public $mentionUsers = [];
    public $selectedMentionIndex = 0;
    public $mentionPosition = 0;

    protected $listeners = ['insertMention', 'closeMentions'];


    public function mount($content = '', $placeholder = 'Digite aqui...', $showWordCount = false, $maxLength = null)
    {
        $this->content = $content;
        $this->placeholder = $placeholder;
        $this->showWordCount = $showWordCount;
        $this->maxLength = $maxLength;
    }

    public function updatedContent($value)
    {
        // Detectar se o usuário digitou @
        $this->detectMentionTrigger($value);

        // Emitir evento para o componente pai
        $this->dispatch('contentUpdated', $this->content);
    }

    public function detectMentionTrigger($content)
    {
        // Log para debug
        logger('Content changed: "' . $content . '"');

        // Pegar a última posição do cursor (simulado pela última mudança)
        $lastAtPosition = strrpos($content, '@');

        if ($lastAtPosition !== false) {
            // Verificar se há texto após o @
            $textAfterAt = substr($content, $lastAtPosition + 1);

            // Log para debug
            logger('Found @ at position: ' . $lastAtPosition . ', text after: "' . $textAfterAt . '"');

            // Se não há espaço ou quebra de linha após @, está digitando uma menção
            if (!str_contains($textAfterAt, ' ') && !str_contains($textAfterAt, "\n")) {
                $this->mentionQuery = $textAfterAt;
                $this->mentionPosition = $lastAtPosition;

                logger('Starting mention with query: "' . $textAfterAt . '"');

                $this->searchUsers($textAfterAt);
                $this->showMentionsList = true;
                $this->selectedMentionIndex = 0;

                logger('Mention list should show: ' . ($this->showMentionsList ? 'true' : 'false') . ', users found: ' . count($this->mentionUsers));
            } else {
                logger('Closing mentions - found space or newline');
                $this->closeMentions();
            }
        } else {
            logger('No @ found, closing mentions');
            $this->closeMentions();
        }
    }

    public function searchUsers($query = '')
    {
        // Se a query estiver vazia ou só espaços, buscar todos os usuários
        if (empty(trim($query))) {

            $this->mentionUsers = User::limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar ?? null,
                    ];
                })
                ->toArray();
        } else {
            // Buscar usuários que correspondem à query
            $this->mentionUsers = User::where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar ?? null,
                    ];
                })
                ->toArray();
        }

        // Log para debug - remova depois
        logger('Query: "' . $query . '", Results: ' . count($this->mentionUsers));
    }

    public function selectUser($userId)
    {
        $user = collect($this->mentionUsers)->firstWhere('id', $userId);

        if ($user) {
            $this->insertMention($user);
        }
    }

    public function insertMention($user)
    {
        // Substituir @query pela menção completa
        $beforeMention = substr($this->content, 0, $this->mentionPosition);
        $afterMention = substr($this->content, $this->mentionPosition + 1 + strlen($this->mentionQuery));

        // Formato da menção: @[Nome do Usuário](user:ID)
        $mention = "@[{$user['name']}](user:{$user['id']})";

        $this->content = $beforeMention . $mention . ' ' . $afterMention;
        $this->closeMentions();

        // Disparar evento JavaScript para focar o editor
        $this->dispatch('mentionInserted');
    }

    public function selectMentionByIndex($index)
    {
        if (isset($this->mentionUsers[$index])) {
            $this->selectUser($this->mentionUsers[$index]['id']);
        }
    }

    public function moveMentionSelection($direction)
    {
        if ($direction === 'up') {
            $this->selectedMentionIndex = max(0, $this->selectedMentionIndex - 1);
        } else {
            $this->selectedMentionIndex = min(count($this->mentionUsers) - 1, $this->selectedMentionIndex + 1);
        }
    }

    public function closeMentions()
    {
        $this->showMentionsList = false;
        $this->mentionQuery = '';
        $this->mentionUsers = [];
        $this->selectedMentionIndex = 0;
    }


    public function render()
    {
        return view('livewire.shared.editor');
    }
}
