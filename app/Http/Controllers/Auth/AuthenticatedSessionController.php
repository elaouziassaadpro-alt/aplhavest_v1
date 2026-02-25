<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthenticatedSessionController extends Controller
{
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
        // ✅ FIRST INSTALL: Create admin if no users exist
        if (User::count() === 0) {
            if ($request->email === 'admin@admin.com' && $request->password === 'admin') {
                $admin = User::create([
                    'name'     => 'Administrator',
                    'email'    => 'admin@admin.com',
                    'password' => Hash::make('admin'),
                    'role'     => User::ROLE_ADMIN,
                    'avatar'   => 'dist/images/profile/user-default.jpg',
                    'status'   => 1,
                ]);

                Auth::login($admin);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.index'));
            }
        }

        // ✅ LOGIN
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user account is active
        if ($user->status == 0) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        }

        // Redirect based on role
        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->intended(route('admin.index')),
            User::ROLE_CI    => redirect()->intended(route('CI.index')),
            User::ROLE_AK    => redirect()->intended(route('AK.index')),
            User::ROLE_BAK   => redirect()->intended(route('BAK.index')),
            default          => redirect()->intended(route('dashboard')),
        };
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
