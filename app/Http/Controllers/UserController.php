<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    public function index(){
        $users = User::all();
       // ✅ returns LengthAwarePaginator
        return view('auth.index', compact('users'));

    
    }

    public function edit(User $user): View
    {
        $avatars = [];
        $path = public_path('dist/images/profile');
        
        if (file_exists($path)) {
            $files = glob($path . '/user-*.jpg');
            if ($files) {
                natsort($files);
                foreach ($files as $file) {
                    $avatars[] = 'dist/images/profile/' . basename($file);
                }
            }
        }

        return view('profile.edit', compact('user', 'avatars'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string'],
            'avatar' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }
}