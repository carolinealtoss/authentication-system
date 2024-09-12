<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Exibe o formulário para inserção do código 2FA.
     */
    public function showVerifyForm()
    {
        return view('auth.2fa.verify');
    }

    /**
     * Verifica o código 2FA inserido pelo usuário.
     */
    public function verify(Request $request)
    {
        // Valida o código 2FA
        $request->validate([
            '2fa_code' => 'required|digits:6',
        ]);

        // Recupera o ID do usuário armazenado na sessão
        $userId = $request->session()->get('2fa:user:id');
        if (!$userId) {
            return redirect()->route('login')->withErrors('Não foi possível verificar o usuário.');
        }

        // Recupera o usuário do banco de dados
        $user = User::find($userId);

        // Verifica se o código 2FA está correto
        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'), 4);

        if ($valid) {
            // Limpa a sessão temporária
            $request->session()->forget('2fa:user:id');

            // Loga o usuário
            Auth::login($user);

            // Redireciona para a página principal
            return redirect()->intended(route('dashboard'));
        } else {
            return back()->withErrors(['2fa_code' => 'Código de autenticação 2FA inválido.']);
        }
    }

    /**
     * Exibe a página para o usuário configurar o 2FA.
     */
    public function setup2FA()
    {
        $userAuth = Auth::user();
        $user = User::find($userAuth->id);

        // Gera o segredo do Google 2FA se ainda não existir
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }

        $google2FAQRCode = (new \PragmaRX\Google2FAQRCode\Google2FA());

        $QR_Image  = $google2FAQRCode->getQRCodeInline(
            $user->name,
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa.setup', [
            'QR_Image' => $QR_Image,
            'secret' => $user->google2fa_secret
        ]);
    }

    /**
     * Habilita o 2FA para o usuário após a verificação do código.
     */
    public function enable2FA(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'), 4);

        if ($valid) {
            return redirect()->route('dashboard')->with('success', 'Autenticação 2FA ativada com sucesso.');
        } else {
            return back()->withErrors(['2fa_code' => 'Código 2FA inválido.']);
        }
    }

    /**
     * Desabilita o 2FA para o usuário.
     */
    public function disable2FA(Request $request)
    {
        $user = Auth::user();

        // Remove o segredo 2FA do banco de dados
        $user->google2fa_secret = null;

        return redirect()->route('dashboard')->with('success', 'Autenticação 2FA desativada com sucesso.');
    }
}
