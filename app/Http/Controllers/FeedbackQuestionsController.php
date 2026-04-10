<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class FeedbackQuestionsController extends Controller
{
    public function index(Request $request)
    {
        $identification = $request->session()->get('feedback_identification_data');

        if (!$identification) {
            return redirect()
                ->route('feedback.identification')
                ->with('error', 'Veuillez vous identifier avant de repondre au questionnaire.');
        }

        $questionnaire = Questionnaire::query()
            ->where('code', 'gitex_feedback_2026')
            ->where('is_active', true)
            ->with(['questions' => function ($query) {
                $query->orderBy('ordre');
            }])
            ->first();

        if (!$questionnaire) {
            abort(404, 'Questionnaire feedback introuvable.');
        }

        $equipeNom = Equipe::query()
            ->where('id', $identification['equipe_id'])
            ->value('nom');

        $questionsBySection = $questionnaire->questions->groupBy(function ($question) {
            return $question->section ?: 'Questions';
        });

        return view('feedback.questions', [
            'questionnaire' => $questionnaire,
            'questionsBySection' => $questionsBySection,
            'identification' => $identification,
            'equipeNom' => $equipeNom,
        ]);
    }
}
