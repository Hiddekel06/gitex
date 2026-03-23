<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ReponseUtilisateur;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return view('questions', compact('questions'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            // Sécurité : session expirée ou non identifié
            return redirect()->route('identification')->with('error', 'Votre session a expiré. Veuillez vous identifier à nouveau.');
        }
        $questions = Question::all();
        $data = $request->all();
        $stop = false;

        foreach ($questions as $i => $question) {
            $reponse = $data['reponse'][$question->id] ?? null;
            $justification = $data['justification'][$question->id] ?? null;
            $intValue = $data['int_value'][$question->id] ?? null;
            if ($reponse === null) continue;
            ReponseUtilisateur::create([
                'user_id' => $userId,
                'question_id' => $question->id,
                'reponse' => $reponse,
                'justification' => $reponse === 'non' ? $justification : null,
                'int_value' => $reponse === 'oui' && $intValue !== null ? $intValue : null,
            ]);
            if ($i === 0 && $reponse === 'non') {
                $stop = true;
                break;
            }
        }
        if ($stop) {
            return view('merci');
        }
        return view('merci'); // À adapter si besoin
    }
}
