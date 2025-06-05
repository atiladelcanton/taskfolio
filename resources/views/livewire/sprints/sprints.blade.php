<div class="flex-1 self-stretch max-md:pt-6">
    <div class="flex md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <flux:heading>Sprint</flux:heading>
            <flux:subheading>Gerencie todas sprints do projeto: <b>{{ $project->name }}</b></flux:subheading>
        </div>
        <div class="flex  sm:flex-row gap-3 w-full md:w-auto">
            <flux:modal.trigger name="modal-sprint">
                <flux:button icon="plus" class="cursor-pointer">Nova Sprint</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    <flux:separator class="my-4"/>
    <flux:card>
        @if ($sprints->isNotEmpty())
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Sprint</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Data de Inicio</flux:table.column>
                    <flux:table.column>Data de Fim</flux:table.column>
                    <flux:table.column>Ações</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($sprints as $sprint)
                        <flux:table.row wire:key="sprint-{{ $sprint->id }}"
                                        class="hover:bg-gray-50 dark:hover:bg-slate-800 hover:cursor-pointer"
                        >
                            <flux:table.cell variant="strong">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                                        <flux:icon name="folder" class="h-5 w-5"/>
                                    </div>
                                    <div class="ml-3">
                                        <a href="#">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $sprint->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-slate-300">{{ $sprint->sprint_code }}</div>
                                        </a>
                                    </div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge>{{ $sprint->status->label() }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm">{{ $sprint->date_start->format('d/m/Y') }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="text-sm">{{ $sprint->date_end->format('d/m/Y') }}</div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon:trailing="chevron-down">Opções</flux:button>
                                    <flux:menu>
                                        <flux:menu.item icon="pencil-square"
                                                        wire:click="editSprint({{ $sprint->id }})"
                                                        class="cursor-pointer">Editar
                                        </flux:menu.item>

                                        <flux:menu.separator />

                                        <flux:menu.item icon="trash" variant="danger"
                                                        wire:click="confirmDeleteSprint({{ $sprint->id }})"
                                                        class="cursor-pointer">
                                            Deletar
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @else
            <div class="overflow-x-auto">
                <flux:heading level="3" class="flex items-center justify-center flex-col">
                    <flux:icon.bolt class="size-12"/>
                    Nenhuma sprint localizada.
                </flux:heading>
            </div>
        @endif
    </flux:card>

    {{--  MODALS --}}
    <flux:modal name="modal-sprint" wire:close="closeModalForm" variant="flyout" class="md:w-96">
        <livewire:sprints.sprint-form-component :projectId="$project->id" />
    </flux:modal>

    <flux:modal name="delete-sprint" class="min-w-[22rem]">
        <livewire:sprints.sprint-delete />
    </flux:modal>
</div>
