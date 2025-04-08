<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    protected $registerUserAction;

    public function __construct(RegisterUserAction $registerUserAction)
    {
        $this->registerUserAction = $registerUserAction;
        $this->middleware('guest');
    }

    /**
     * Mostra o formulário de registro
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Registra um novo usuário
     */
    public function register(RegisterUserRequest $request): RedirectResponse
    {
        // Obter dados validados
        $validatedData = $request->validated();

        // Registrar o usuário
        $this->registerUserAction->execute($validatedData);

        // Redirecionar para a página de verificação
        return redirect()->route('verification.notice')
            ->with('success', 'Conta criada com sucesso! Por favor, verifique seu e-mail para ativar sua conta.');
    }
}
