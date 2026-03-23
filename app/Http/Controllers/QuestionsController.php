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
        $questions = Question::all();
        $data = $request->all();
        $stop = false;

        foreach ($questions as $i => $question) {
            $reponse = $data['reponse'][$question->id] ?? null;
            $justification = $data['justification'][$question->id] ?? null;
            if ($reponse === null) continue;
            ReponseUtilisateur::create([
                'user_id' => $userId,
                'question_id' => $question->id,
                'reponse' => $reponse,
                'justification' => $reponse === 'non' ? $justification : null,
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
