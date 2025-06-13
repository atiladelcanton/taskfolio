{{-- resources/views/livewire/shared/editor-with-mentions.blade.php --}}
<div class="relative border border-gray-300 rounded-lg p-4 bg-white"
     x-data="mentionsEditor()"
     x-init="init()"
     @keydown.escape="$wire.closeMentions()"
     wire:ignore.self>

    <div class="relative">
        <flux:editor
            wire:model.live.debounce.300ms="content"
            placeholder="{{ $placeholder }}"
            class="min-h-[200px] border-none outline-none w-full"
            x-ref="editor"
            @keydown="handleKeydown($event)"
            @input="handleInput($event)"
        />

        {{-- Lista de menções --}}
        @if($showMentionsList && count($mentionUsers) > 0)
            <div class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto z-50 mt-1"
                 wire:transition>
                <div class="px-3 py-2 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                    <small class="text-gray-500">Selecione um usuário para mencionar</small>
                </div>

                @foreach($mentionUsers as $index => $user)
                    <div class="flex items-center px-3 py-2 cursor-pointer transition-colors hover:bg-blue-50 {{ $selectedMentionIndex === $index ? 'bg-blue-50' : '' }}"
                         wire:click="selectUser({{ $user['id'] }})"
                         @mouseover="$wire.set('selectedMentionIndex', {{ $index }})">

                        <div class="w-8 h-8 mr-3 rounded-full overflow-hidden flex-shrink-0">
                            @if($user['avatar'])
                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-blue-500 text-white flex items-center justify-center font-bold text-sm uppercase">
                                    {{ substr($user['name'], 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $user['name'] }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $user['email'] }}</div>
                        </div>
                    </div>
                @endforeach

                @if(count($mentionUsers) === 0 && $mentionQuery)
                    <div class="px-3 py-2 text-gray-500 cursor-default">
                        <span>Nenhum usuário encontrado</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($showWordCount)
        <div class="mt-2 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                {{ str_word_count($content) }} palavras
                @if($maxLength)
                    / {{ strlen($content) }} de {{ $maxLength }} caracteres
                @endif
            </div>

        </div>
    @endif
</div>

@section('js')

<script>
    function mentionsEditor() {
        return {
            init() {
                // Escutar eventos do Livewire

                this.$wire.on('mentionInserted', () => {
                    console.log('sdsds');
                    // Focar o editor após inserir menção
                    this.$nextTick(() => {
                        this.focusEditor();
                    });
                });
            },

            handleKeydown(event) {
                // Se a lista de menções está aberta
                if (this.$wire.showMentionsList) {
                    switch(event.key) {
                        case 'ArrowDown':
                            event.preventDefault();
                            this.$wire.moveMentionSelection('down');
                            break;
                        case 'ArrowUp':
                            event.preventDefault();
                            this.$wire.moveMentionSelection('up');
                            break;
                        case 'Enter':
                            event.preventDefault();
                            this.$wire.selectMentionByIndex(this.$wire.selectedMentionIndex);
                            break;
                        case 'Escape':
                            event.preventDefault();
                            this.$wire.closeMentions();
                            break;
                    }
                }
            },

            handleInput(event) {
                // Debounce para pesquisa de usuários
                const content = event.target.textContent || event.target.value;

                // Detectar posição do cursor e @ mais próximo
                // Este é um exemplo simplificado - pode precisar de ajustes
                // dependendo de como o Flux Editor funciona
            },

            focusEditor() {
                if (this.$refs.editor) {
                    this.$refs.editor.focus();
                }
            }
        }
    }
</script>
@endsection
