<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

test('novos usuários podem se registrar via livewire', function () {
    // Teste o componente Livewire
    $component = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register');
    
    // Verifica se o usuário foi criado no banco de dados
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User'
    ]);
    
    // Verifica redirecionamento para a página de verificação
    $component->assertRedirect(route('verification.notice'));
});