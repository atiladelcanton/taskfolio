<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nome Completo')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Jeremias Carcamano')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="emailConfirmation"
            :label="__('E-mail')"
            type="email"
            required
            disabled
            autocomplete="email"
            placeholder="email@email.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Senha')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
         />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar a Senha')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Criar conta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
