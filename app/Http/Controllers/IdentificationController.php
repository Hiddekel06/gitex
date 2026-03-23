<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;

class IdentificationController extends Controller
{

    public function showForm()
    {
        // On ne propose que les équipes non utilisées
        $equipesUtilisees = User::pluck('equipe_id')->toArray();
        $equipes = Equipe::whereNotIn('id', $equipesUtilisees)->get();
        return view('identification', compact('equipes'));
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'equipe_id' => 'required|exists:equipes,id',
        ]);

        // Vérifier si l'équipe est déjà utilisée
        $equipeDejaUtilisee = User::where('equipe_id', $validated['equipe_id'])->exists();
        if ($equipeDejaUtilisee) {
            return back()->withErrors(['equipe_id' => 'Cette équipe a déjà été utilisée.'])->withInput();
        }

        // On flush la session pour éviter toute collision d'utilisateur précédent
        $request->session()->flush();

        // Créer l'utilisateur (on ne stocke que l'email et l'équipe, nom/prénom si besoin)
        $user = User::create([
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'password' => '', // Pas de mot de passe requis
            'equipe_id' => $validated['equipe_id'],
        ]);

        // Stocker l'utilisateur en session (simple, pas d'auth Laravel)
        session(['user_id' => $user->id]);

        return redirect()->route('questions.index'); // À adapter selon la suite
    }
}
