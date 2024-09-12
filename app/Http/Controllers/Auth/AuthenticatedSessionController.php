<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentica o usuário com e-mail e senha
        $request->authenticate();

        // Recupera o usuário autenticado
        $user = Auth::user();

        //dd($request, $user);

        // Verifica se o 2FA está habilitado para o usuário
        if ($user->google2fa_secret) {
            // Armazena o ID do usuário na sessão até que o 2FA seja verificado
            $request->session()->put('2fa:user:id', $user->id);

            // Desloga o usuário temporariamente para que o 2FA seja verificado
            Auth::logout();

            // Redireciona para a página de verificação do código 2FA
            return redirect()->route('verify.2fa');
        }

        // Se o 2FA não estiver habilitado, prossegue com o login normal
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
