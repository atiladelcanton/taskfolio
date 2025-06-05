<div>
    <div class="space-y-6">
        <div class="space-y-6">
            <form wire:submit="save">
                <flux:heading size="lg">{{ $title }}</flux:heading>

                <flux:field class="mt-5 mb-5">
                    <flux:input wire:model="sprintForm.name" label="Nome da Sprint" placeholder="Sprint 1"/>
                </flux:field>

                <flux:date-picker mode="range" locale="pt-BR" wire:model="sprintForm.range" class="mb-5">
                    <x-slot name="trigger">
                        <div class="flex flex-col sm:flex-row gap-6 sm:gap-4">
                            <flux:date-picker.input label="Início" />
                            <flux:date-picker.input label="Final" />
                        </div>
                    </x-slot>
                </flux:date-picker>
                <flux:error name="sprintForm.range.start" />
                <flux:error name="sprintForm.range.end" />

                @if ($sprintId)
                    <flux:select variant="listbox" label="Status" placeholder="Status" wire:model="sprintForm.status">
                        @foreach(\App\Domain\Sprint\Enums\SprintStatus::cases() as $status)
                            <flux:select.option value="{{ $status->value }}" wire:key="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif

                <div class="flex md:flex-row justify-between items-start md:items-center mt-5">
                    <flux:button variant="primary" type="submit" class="cursor-pointer">
                        {{ $btnSubmitText }}
                    </flux:button>
                    <flux:modal.close>
                        <flux:button variant="subtle" class="cursor-pointer">Cancelar</flux:button>
                    </flux:modal.close>
                </div>
            </form>
        </div>
    </div>
</div>
