<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ReponseUtilisateur;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $hasAlreadyAnswered = false;
        if ($userId) {
            $hasAlreadyAnswered = \App\Models\ReponseUtilisateur::where('user_id', $userId)->exists();
        }
        $questions = Question::all();
        if ($hasAlreadyAnswered) {
            return view('merci', ['alreadyAnswered' => true]);
        }
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

            // Cas question directe int : on prend la valeur même si pas de radio
            $isDirectInt = ($question->type_question === 'direct' && $question->type_reponse === 'int');
            $hasValue = $reponse !== null || ($isDirectInt && $intValue !== null);
            if (!$hasValue) continue;

            // Empêcher l'insertion de doublons
            $exists = ReponseUtilisateur::where('user_id', $userId)
                ->where('question_id', $question->id)
                ->exists();
            if (!$exists) {
                ReponseUtilisateur::create([
                    'user_id' => $userId,
                    'question_id' => $question->id,
                    'reponse' => $isDirectInt ? null : $reponse,
                    'justification' => $reponse === 'non' ? $justification : null,
                    'int_value' => $isDirectInt ? $intValue : ($reponse === 'oui' && $intValue !== null ? $intValue : null),
                ]);
            }
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
