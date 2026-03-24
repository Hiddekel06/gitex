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
        $identification = session('identification_data');
        $hasAlreadyAnswered = false;
        if ($userId) {
            $hasAlreadyAnswered = \App\Models\User::where('id', $userId)->where('completed', true)->exists();
        }
        $questions = Question::all();
        if ($hasAlreadyAnswered) {
            return view('merci', ['alreadyAnswered' => true]);
        }
        // Si pas d'identification, retour à l'identification
        if (!$userId && !$identification) {
            return redirect()->route('identification')->with('error', 'Veuillez vous identifier.');
        }
        return view('questions', compact('questions'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        $identification = session('identification_data');
        if (!$userId && !$identification) {
            // Sécurité : session expirée ou non identifié
            return redirect()->route('identification')->with('error', 'Votre session a expiré. Veuillez vous identifier à nouveau.');
        }
        $questions = Question::all();
        $data = $request->all();
        $stop = false;

        // Si pas encore d'utilisateur en base, on le crée maintenant
        if (!$userId && $identification) {
            $user = \App\Models\User::create([
                'name' => $identification['name'],
                'email' => $identification['email'],
                'password' => '',
                'equipe_id' => $identification['equipe_id'],
                'completed' => false,
            ]);
            session(['user_id' => $user->id]);
            $userId = $user->id;
        } else {
            $user = \App\Models\User::find($userId);
        }

        foreach ($questions as $i => $question) {
            $reponse = $data['reponse'][$question->id] ?? null;
            $justification = $data['justification'][$question->id] ?? null;
            $intValue = $data['int_value'][$question->id] ?? null;

            // Cas question directe int : on prend la valeur même si pas de radio
            $isDirectInt = ($question->type_question === 'direct' && $question->type_reponse === 'int');
            $hasValue = $reponse !== null || ($isDirectInt && $intValue !== null);
            if (!$hasValue) continue;

            // Empêcher l'insertion de doublons
            $exists = \App\Models\ReponseUtilisateur::where('user_id', $userId)
                ->where('question_id', $question->id)
                ->exists();
            if (!$exists) {
                \App\Models\ReponseUtilisateur::create([
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

        // Marquer l'utilisateur comme ayant terminé le questionnaire
        if ($user) {
            $user->completed = true;
            $user->save();
        }
        // Nettoyer la session d'identification
        session()->forget('identification_data');

        return view('merci');
    }
}
