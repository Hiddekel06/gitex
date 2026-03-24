<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;

class IdentificationController extends Controller
{

    public function showForm()
    {
        // On ne propose que les équipes non utilisées par un utilisateur ayant terminé le questionnaire
        $equipesUtilisees = User::where('completed', true)->pluck('equipe_id')->toArray();
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

        // On flush la session pour éviter toute collision d'utilisateur précédent
        $request->session()->flush();

        // Créer l'utilisateur en session uniquement (pas encore en base)
        session([
            'identification_data' => [
                'name' => $validated['prenom'] . ' ' . $validated['nom'],
                'email' => $validated['email'],
                'equipe_id' => $validated['equipe_id'],
            ]
        ]);

        return redirect()->route('questions.index');
    }
}
