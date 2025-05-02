<div>
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Você deseja remover esta sprint?</flux:heading>
            <flux:text class="mt-2">
                <p>Se você prosseguir a sprint e as tarefas serão excluídos.</p>
                <p>Essa ação não poderá ser desfeita.</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer/>
            <flux:modal.close>
                <flux:button variant="ghost">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button wire:click="remove" class="cursor-alias" variant="danger">Deletar Sprint
            </flux:button>
        </div>
    </div>
</div>
