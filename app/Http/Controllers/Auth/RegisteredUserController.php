<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $avatars = [];
        $path = public_path('dist/images/profile');
        
        if (file_exists($path)) {
            $files = glob($path . '/user-*.jpg');
            if ($files) {
                natsort($files); // Sort naturally (user-1, user-2, ... user-10)
                foreach ($files as $file) {
                    $avatars[] = 'dist/images/profile/' . basename($file);
                }
            }
        }

        return view('auth.register', compact('avatars'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:AK,CI,BAK,admin'],
            'avatar' => ['required', 'string'],

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $request->avatar,
        ]);

        event(new Registered($user));



        return redirect()->back()->with('success', 'le compte a été créé avec succès.');
    }
}
