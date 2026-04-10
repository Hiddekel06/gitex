<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use Illuminate\Http\Request;

class FeedbackIdentificationController extends Controller
{
    public function showForm()
    {
        // Tous les membres d'equipe sont autorises: aucune exclusion d'equipe.
        $equipes = Equipe::orderBy('nom')->get();

        return view('feedback.identification', compact('equipes'));
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'equipe_id' => 'required|exists:equipes,id',
        ]);

        // Session dediee au nouveau flux pour eviter tout conflit avec l'ancien.
        $request->session()->forget(['feedback_identification_data', 'feedback_user_id']);
        $request->session()->put('feedback_identification_data', [
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'equipe_id' => $validated['equipe_id'],
        ]);

        return redirect()->route('feedback.questions.index');
    }
}
