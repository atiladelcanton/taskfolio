{{-- resources/views/livewire/task.blade.php --}}
<div>
    <livewire:shared.editor
        :value="$content"
        placeholder="Descreva a tarefa"
        :show-word-count="true"
        :key="'task-editor-' . $type"
    />

    <div class="mt-3">
        <h4>Conteúdo atual:</h4>
        <div class="alert alert-info">
            {{ $content ?: 'Nenhum conteúdo digitado ainda...' }}
        </div>
    </div>

    {{-- Debug info - remova depois --}}
    <div class="mt-2 text-sm text-gray-500">
        Debug: Tamanho do conteúdo = {{ strlen($content) }}
    </div>
</div>
