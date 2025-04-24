@php use App\Domain\Team\RoleTeamEnum; @endphp
<div class="flex-1 self-stretch max-md:pt-6">
    <div class="flex md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <flux:heading class="text-2xl">
                Times
            </flux:heading>
            <flux:subheading>Adicione, Remova e Gerencie todo(a)s as pessoas que fazem parte do seu time
            </flux:subheading>
        </div>
        <div class="flex sm:flex-row gap-3 w-full md:w-auto">
            <flux:modal.trigger name="new-colaborator">
                <flux:button icon="plus" class="cursor-pointer">Adicionar Colaborador</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    <flux:separator class="my-4"/>
    <flux:card>
        <div class="overflow-x-auto">
            @if(count($members ?? []) === 0)
                <flux:heading level="3" class="flex items-center justify-center flex-col">
                    <flux:icon.bolt class="size-12"/>
                    Você não tem nenhum colaborador em seu time. Adicione colaboradores para começar.
                </flux:heading>
            @else
                <flux:table :paginate="$members">
                    <flux:table.columns>
                        <flux:table.column>Nome</flux:table.column>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Valor Cobrado</flux:table.column>
                        <flux:table.column>Valor Pago</flux:table.column>
                        <flux:table.column>Tipo de Cobrança</flux:table.column>
                        <flux:table.column>Perfil</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Ações</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($members as $member)
                            <flux:table.row>
                                <flux:table.cell>{{ $member->name ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>{{ $member->email }}</flux:table.cell>
                                <flux:table.cell>{{  number_format($member->billing_rate, 2, ',', '.') }}</flux:table.cell>
                                <flux:table.cell>{{  number_format($member->cost_rate, 2, ',', '.') }}</flux:table.cell>
                                <flux:table.cell>
                                    @switch($member->billing_type)
                                        @case(1)
                                            Por Hora
                                            @break
                                        @case(2)
                                            Por Projeto
                                            @break
                                        @case(3)
                                            Sem Cobrança
                                            @break
                                        @default
                                            {{ $member->billing_type }}
                                    @endswitch
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge>{{ RoleTeamEnum::getRolesName($member->role) }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge color="{{ $member->status === 'ativo' ? 'lime' : 'amber' }}">
                                        {{\Illuminate\Support\Str::ucfirst( $member->status) }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:dropdown>
                                        <flux:button icon:trailing="chevron-down">Opções</flux:button>
                                        <flux:menu>
                                            @if($member->status === 'pendente')
                                                <flux:menu.item icon="mail"
                                                                wire:click="resend({{ $member->id }})"
                                                                class="cursor-pointer">
                                                    Reenviar
                                                </flux:menu.item>
                                            @endif
                                            @if($member->status === 'ativo')
                                                <flux:menu.item icon="pencil-square"
                                                                wire:click="editProject({{ $member->id }})"
                                                                class="cursor-pointer">Editar
                                                </flux:menu.item>
                                            @endif

                                            @if($member->role !== RoleTeamEnum::Owner->value)
                                                    <flux:menu.separator/>
                                                @if($member->status === 'pendente')
                                                    <flux:menu.item icon="trash" variant="danger"
                                                                    wire:click="confirmDeleteInvite({{ $member->id }})"
                                                                    class="cursor-pointer">
                                                        Excluir Convite
                                                    </flux:menu.item>

                                                @else
                                                    <flux:menu.item icon="trash" variant="danger"
                                                                    wire:click="confirmDeleteCollaborator({{ $member->id }})"
                                                                    class="cursor-pointer">
                                                        Remover
                                                    </flux:menu.item>
                                                @endif
                                            @endif

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
    <flux:modal name="new-colaborator" wire:close="closeModal" variant="flyout">
        <div class="space-y-6">
            <form wire:submit="addColaborator">
                <div class="space-y-6">
                    <flux:heading size="lg">Adicionar Colaborador</flux:heading>
                </div>

                <div class="space-y-6 my-4">
                    <flux:input wire:model="colaboratorForm.email" type="email" label="Email do Colaborador"
                                placeholder="exemplo@email.com"/>
                    @error('colaboratorForm.email')
                    <flux:text variant="danger" class="mt-1">{{ $message }}</flux:text>
                    @enderror
                </div>
                <div class="space-y-6 my-4">
                    <flux:select
                        required
                        wire:model="colaboratorForm.role"
                        label="Tipo de Colaborador"
                    >
                        <flux:select.option value="0" wire:key="0">Selecione o tipo de colaborador</flux:select.option>
                        @foreach($rolesTeam as $key => $type)
                            <flux:select.option value="{{$type[0]}}">{{$type[1]}}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="space-y-6 my-4">
                    <flux:select
                        required
                        wire:model="colaboratorForm.billing_type"
                        label="Tipo de Cobrança"
                    >
                        <flux:select.option value="0">Selecione o tipo de cobrança</flux:select.option>
                        <flux:select.option value="1">Por Hora</flux:select.option>
                        <flux:select.option value="2">Por Projeto</flux:select.option>
                        <flux:select.option value="3">Sem Cobrança</flux:select.option>
                    </flux:select>
                </div>
                <div class="my-6">
                    <flux:input
                        type="number"
                        required
                        wire:model="colaboratorForm.billing_rate"
                        label="Valor Pago"
                        placeholder="100"
                    />
                </div>
                <div class="my-6">
                    <flux:input
                        type="number"
                        required
                        wire:model="colaboratorForm.cost_rate"
                        label="Valor Cobrado"
                        placeholder="100"
                    />
                </div>
                <div class="flex md:flex-row justify-between items-start md:items-center">
                    <flux:modal.close>
                        <flux:button variant="subtle" class="cursor-pointer">Cancelar</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" type="submit" class="cursor-pointer">Adicionar Colaborador
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="delete-collaborator" wire:close="closeModal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Você deseja remover este colaborador?</flux:heading>
                <flux:text class="mt-2">
                    <p>Essa ação não poderá ser desfeita.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteColaborator" class="cursor-alias" variant="danger">Remover ColaboradorN
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
