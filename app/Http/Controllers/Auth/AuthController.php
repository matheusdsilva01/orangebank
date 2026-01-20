<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\LogoutRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(AuthenticateRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt(['email' => $credentials['identifier'], 'password' => $credentials['password']]) ||
            Auth::attempt(['cpf' => $credentials['identifier'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'identifier' => __('auth.failed'),
            'password' => __('auth.failed'),
        ]);
    }

    public function logout(LogoutRequest $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
