<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // User Statistics
        $userStats = [
            'total' => User::count(),
            'admins' => User::role('admin')->count(),
            'ak' => User::role('AK')->count(),
            'bak' => User::role('BAK')->count(),
            'ci' => User::role('CI')->count(),
        ];

        // Establishment Statistics
        $establishmentStats = [
            'total' => Etablissement::count(),
            'valide' => Etablissement::where('validation_AK', 1)->count(),
            'en_attente' => Etablissement::whereNull('validation_AK')->count(),
            'rejete' => Etablissement::where('validation_AK', 0)->count(),
        ];

        // Recent Activity
        $recentUsers = User::latest()->take(5)->get();
        $recentEtablissements = Etablissement::latest()->take(5)->get();

        return view('admin', compact('userStats', 'establishmentStats', 'recentUsers', 'recentEtablissements'));
    }

    /**
     * Delete a user.
     */
    public function destroyUser(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Update user status and role.
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1',
            'role' => 'required|string|in:admin,AK,BAK,CI',
        ]);
        if ($user->role == 'admin') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier un admin.');
        }

        $user->update([
            'status' => $validated['status'],
            'role' => $validated['role'],
        ]);

        return redirect()->back()->with('success', 'Statut de l\'utilisateur mis à jour.');
    }
}
