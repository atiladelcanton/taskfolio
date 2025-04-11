<div class="flex-1 self-stretch max-md:pt-6">
    <div class="flex md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <flux:heading>Projetos</flux:heading>
            <flux:subheading>Gerencie todos os projetos da sua organização</flux:subheading>
        </div>
        <div class="flex  sm:flex-row gap-3 w-full md:w-auto">
            <flux:input placeholder="Buscar projetos..." wire:model.live.debounce.300ms="searchTerm" class="md:w-64"/>
            <flux:modal.trigger name="new-project">
                <flux:button icon="plus" class="cursor-pointer">Novo Projeto</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    <flux:separator class="my-4"/>
    <flux:card>
        <div class="overflow-x-auto">
            @if(count($projects) === 0)
                <flux:heading level="3" class="flex items-center justify-center flex-col">
                    <flux:icon.bolt class="size-12"/>
                    Nenhum projeto, localizado ou voce nao criou nenhum projeto
                </flux:heading>
            @else
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Projeto</flux:table.column>
                        <flux:table.column sortable>Data de Criação</flux:table.column>
                        <flux:table.column sortable>Total de Sprints</flux:table.column>
                        <flux:table.column>Participantes</flux:table.column>
                        <flux:table.column>Ações</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($projects as $project)
                            <flux:table.row wire:key="project-{{ $project->id }}"
                                            class="hover:bg-gray-50 dark:hover:bg-slate-800 hover:cursor-pointer"
                            >
                                <flux:table.cell variant="strong">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                                            <flux:icon name="folder" class="h-5 w-5"/>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('sprints.index', $project->project_code) }}">
                                                <div class="font-medium text-gray-900 dark:text-white">

                                                    {{$project->name}}</div>
                                                <div
                                                    class="text-sm text-gray-500 dark:text-slate-300">{{$project->project_code}}</div>
                                            </a>
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="text-sm">{{ $project->created_at->format('d/m/Y') }}</div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm font-semibold">{{ $project->sprints()->count() ?? 0 }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:avatar.group>
                                        @foreach($project->users->take(5) as $user)
                                            @if($user->avatar)
                                                <flux:avatar
                                                    circle
                                                    src="{{asset('storage/' . $user->avatar)}}"
                                                    title="{{ $user->name }}"
                                                />
                                                @else
                                            <flux:avatar
                                                circle
                                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                                title="{{ $user->name }}"
                                            />
                                            @endif
                                        @endforeach
                                        @if($project->users->count() > 5)
                                            <flux:avatar>{{ $project->users->count() - 5 }}+</flux:avatar>
                                        @endif
                                    </flux:avatar.group>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:dropdown>
                                        <flux:button icon:trailing="chevron-down">Opcoes</flux:button>
                                        <flux:menu>
                                            <flux:menu.item icon="pencil-square"
                                                            wire:click="editProject({{ $project->id }})"
                                                            class="cursor-pointer">Editar
                                            </flux:menu.item>
                                            <flux:menu.item icon="plus"
                                                            wire:click="addParticipants({{ $project->id }})"
                                                            class="cursor-pointer">Adicionar Participantes
                                            </flux:menu.item>
                                            <flux:menu.separator />

                                            <flux:menu.item icon="trash" variant="danger"
                                                            wire:click="confirmDeleteProject({{ $project->id }})"
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
            @endif
        </div>
    </flux:card>

    {{--    MODALS --}}
    <flux:modal name="new-project" wire:close="closeModal" variant="flyout">
        <div class="space-y-6">
            <div class="space-y-6">
                <form wire:submit="{{$projectId === 0 ? 'createProject': 'updateProject'}}">
                    <flux:heading size="lg">{{$projectId === 0 ? 'Novo Projeto': 'Editar Projeto'}}</flux:heading>
                    <flux:text class="mt-2 mb-4">Informe o nome do seu projeto.</flux:text>
                    <flux:input class="py-6" wire:model="projectForm.name" label="Nome do Projeto" placeholder="Projeto Laravel"/>
                    <flux:textarea class="my-4" wire:model="projectForm.description" label="Descricao do Projeto"
                                   description="Breve descricao do projeto"/>

                    <div class="flex md:flex-row justify-between items-start md:items-center">
                        <flux:modal.close>
                            <flux:button variant="subtle" class="cursor-pointer">Cancelar</flux:button>
                        </flux:modal.close>
                        <flux:button variant="primary" type="submit" class="cursor-pointer">
                            {{$projectId === 0 ? 'Criar Projeto': 'Alterar Projeto'}}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="delete-project" wire:close="closeModal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Você deseja remover este projeto?</flux:heading>
                <flux:text class="mt-2">
                    <p>Se você prosseguir, o projeto, a sprint e as tarefas serão excluídos.</p>
                    <p>Essa ação não poderá ser desfeita.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteProject" class="cursor-alias" variant="danger">Deletar Projeto
                </flux:button>
            </div>
        </div>
    </flux:modal>


    <flux:modal name="add-participants-project" variant="flyout" wire:close="closeModal">
        <div class="space-y-6">
            <div class="space-y-6">
                <form wire:submit="{{$projectId === 0 ? 'createProject': 'updateProject'}}">
                    <flux:heading size="lg">Adicionar Participantes</flux:heading>
                    <flux:text class="mt-2 mb-4">Adicione pessoas para poder utilizar o projeto.</flux:text>
                    <flux:select class="m-4" multiple variant="listbox"  placeholder="Selecione os Participantes"  wire:model="syncParticipants">
                        @if($usersInMyProject)
                            @foreach($usersInMyProject as $user)
                                <flux:select.option value="{{$user->user_id}}" >{{$user->name}}</flux:select.option>
                            @endforeach
                        @endif
                    </flux:select>
                    <div class="flex md:flex-row justify-between items-start md:items-center">
                        <flux:modal.close>
                            <flux:button variant="subtle" class="cursor-pointer">Cancelar</flux:button>
                        </flux:modal.close>
                        <flux:button variant="primary" type="submit" class="cursor-pointer" wire:click="syncParticipantsToProject">
                            Adicionar Participantes
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </flux:modal>
    {{--    --}}
</div>
