<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\FeedbackSubmission;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\ReponseUtilisateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeedbackQuestionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->get('feedback_completed')) {
            return redirect()->route('feedback.thankyou');
        }

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

        $alreadySubmitted = FeedbackSubmission::query()
            ->where('email', $identification['email'])
            ->where('questionnaire_id', $questionnaire->id)
            ->exists();

        if ($alreadySubmitted) {
            $request->session()->forget('feedback_identification_data');

            return redirect()
                ->route('feedback.identification')
                ->withErrors(['email' => 'Vous avez deja soumis vos reponses.'])
                ->withInput();
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

    public function store(Request $request)
    {
        if ($request->session()->get('feedback_completed')) {
            return redirect()->route('feedback.thankyou');
        }

        $identification = $request->session()->get('feedback_identification_data');

        if (!$identification) {
            return redirect()
                ->route('feedback.identification')
                ->with('error', 'Votre session a expire. Veuillez vous identifier a nouveau.');
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

        $choices = $request->input('reponse_choice', []);
        $reponses = $request->input('reponse', []);
        $intValues = $request->input('int_value', []);
        $nonJustifications = $request->input('justification_non', []);

        $errors = [];
        $rows = [];

        foreach ($questionnaire->questions as $question) {
            $questionId = $question->id;
            $choice = isset($choices[$questionId]) ? trim((string) $choices[$questionId]) : null;
            $reponseValue = isset($reponses[$questionId]) ? trim((string) $reponses[$questionId]) : null;
            $intValue = $intValues[$questionId] ?? null;
            $nonJustification = isset($nonJustifications[$questionId]) ? trim((string) $nonJustifications[$questionId]) : null;

            if ($this->isPotentialConnectionsQuestion($question)) {
                $triggerQuestion = $this->findContactsQuestion($questionnaire);
                $triggerAnswer = $triggerQuestion ? ($choices[$triggerQuestion->id] ?? null) : null;

                if ($triggerAnswer !== 'oui') {
                    continue;
                }

                if ($question->is_required && empty($reponseValue)) {
                    $errors["reponse.$questionId"] = 'Veuillez preciser la suite de votre projet.';
                    continue;
                }

                if ($reponseValue !== null && $reponseValue !== '') {
                    $rows[] = [
                        'question_id' => $questionId,
                        'reponse' => $reponseValue,
                        'justification' => null,
                        'int_value' => null,
                    ];
                }

                continue;
            }

            if ($this->isContributionQuestion($question)) {
                if ($question->is_required && !in_array($choice, ['oui', 'non'], true)) {
                    $errors["reponse_choice.$questionId"] = 'Veuillez choisir Oui ou Non.';
                    continue;
                }

                if ($choice === 'oui' && empty($reponseValue)) {
                    $errors["reponse.$questionId"] = 'Veuillez preciser comment votre projet va evoluer.';
                    continue;
                }

                if ($choice === 'non' && empty($nonJustification)) {
                    $errors["justification_non.$questionId"] = 'Veuillez justifier votre reponse.';
                    continue;
                }

                $rows[] = [
                    'question_id' => $questionId,
                    'reponse' => $choice,
                    'justification' => $choice === 'oui' ? $reponseValue : ($choice === 'non' ? $nonJustification : null),
                    'int_value' => null,
                ];
                continue;
            }

            if ($this->isRecommendationQuestion($question)) {
                if ($question->is_required && !in_array($choice, ['oui', 'non'], true)) {
                    $errors["reponse_choice.$questionId"] = 'Veuillez choisir Oui ou Non.';
                    continue;
                }

                if ($question->is_required && empty($reponseValue)) {
                    $errors["reponse.$questionId"] = 'Veuillez renseigner pourquoi.';
                    continue;
                }

                $rows[] = [
                    'question_id' => $questionId,
                    'reponse' => $choice,
                    'justification' => $reponseValue ?: null,
                    'int_value' => null,
                ];
                continue;
            }

            if ($question->type_reponse === 'yes_no') {
                if ($question->is_required && !in_array($reponseValue, ['oui', 'non'], true)) {
                    $errors["reponse.$questionId"] = 'Veuillez choisir Oui ou Non.';
                    continue;
                }

                if ($reponseValue === 'non' && empty($nonJustification)) {
                    $errors["justification_non.$questionId"] = 'Veuillez justifier votre reponse.';
                    continue;
                }

                if ($reponseValue !== null && $reponseValue !== '') {
                    $rows[] = [
                        'question_id' => $questionId,
                        'reponse' => $reponseValue,
                        'justification' => $reponseValue === 'non' ? $nonJustification : null,
                        'int_value' => null,
                    ];
                }
                continue;
            }

            if ($question->type_reponse === 'rating_1_5') {
                $rating = is_numeric($reponseValue) ? (int) $reponseValue : null;

                if ($question->is_required && ($rating === null || $rating < 1 || $rating > 5)) {
                    $errors["reponse.$questionId"] = 'Veuillez selectionner une note entre 1 et 5.';
                    continue;
                }

                if ($rating !== null) {
                    $rows[] = [
                        'question_id' => $questionId,
                        'reponse' => (string) $rating,
                        'justification' => null,
                        'int_value' => $rating,
                    ];
                }
                continue;
            }

            if ($question->type_reponse === 'int') {
                $numeric = is_numeric($intValue) ? (int) $intValue : null;

                if ($question->is_required && $numeric === null) {
                    $errors["int_value.$questionId"] = 'Veuillez renseigner une valeur numerique.';
                    continue;
                }

                if ($numeric !== null) {
                    $rows[] = [
                        'question_id' => $questionId,
                        'reponse' => (string) $numeric,
                        'justification' => null,
                        'int_value' => $numeric,
                    ];
                }
                continue;
            }

            if ($question->is_required && empty($reponseValue)) {
                $errors["reponse.$questionId"] = 'Ce champ est obligatoire.';
                continue;
            }

            if ($reponseValue !== null && $reponseValue !== '') {
                $rows[] = [
                    'question_id' => $questionId,
                    'reponse' => $reponseValue,
                    'justification' => null,
                    'int_value' => null,
                ];
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $user = User::query()->firstOrCreate(
            ['email' => $identification['email']],
            [
                'name' => $identification['name'],
                'password' => 'feedback-flow',
                'equipe_id' => $identification['equipe_id'],
                'completed' => false,
            ]
        );

        $user->update([
            'name' => $identification['name'],
            'equipe_id' => $identification['equipe_id'],
        ]);

        $questionIds = $questionnaire->questions->pluck('id');
        ReponseUtilisateur::query()
            ->where('user_id', $user->id)
            ->whereIn('question_id', $questionIds)
            ->delete();

        foreach ($rows as $row) {
            ReponseUtilisateur::query()->create([
                'user_id' => $user->id,
                'question_id' => $row['question_id'],
                'reponse' => $row['reponse'],
                'justification' => $row['justification'],
                'int_value' => $row['int_value'],
            ]);
        }

        FeedbackSubmission::query()->updateOrCreate(
            [
                'questionnaire_id' => $questionnaire->id,
                'email' => $identification['email'],
            ],
            [
                'user_id' => $user->id,
                'equipe_id' => $identification['equipe_id'],
                'submitted_at' => now(),
            ]
        );

        $request->session()->put('feedback_user_id', $user->id);
        $request->session()->put('feedback_completed', true);
        $request->session()->forget('feedback_identification_data');

        return redirect()->route('feedback.thankyou');
    }

    public function thankYou(Request $request)
    {
        if (!$request->session()->get('feedback_completed')) {
            return redirect()->route('feedback.identification');
        }

        $request->session()->forget(['feedback_identification_data', 'feedback_user_id']);

        return view('feedback.merci');
    }

    private function isContributionQuestion(Question $question): bool
    {
        $text = $this->normalize($question->intitule);

        return str_contains($text, 'cette experience va-t-elle contribuer')
            && str_contains($text, 'si oui, comment');
    }

    private function isRecommendationQuestion(Question $question): bool
    {
        $text = $this->normalize($question->intitule);

        return str_contains($text, 'recommanderiez-vous ce type d')
            && str_contains($text, 'pourquoi');
    }

    private function isPotentialConnectionsQuestion(Question $question): bool
    {
        $text = $this->normalize($question->intitule);

        return str_contains($text, 'ces connexions ont-elles un potentiel concret pour la suite de votre projet');
    }

    private function findContactsQuestion(Questionnaire $questionnaire): ?Question
    {
        foreach ($questionnaire->questions as $question) {
            $text = $this->normalize($question->intitule);

            if (str_contains($text, 'avez-vous pu etablir des contacts interessants')) {
                return $question;
            }
        }

        return null;
    }

    private function normalize(string $value): string
    {
        return (string) Str::of($value)->ascii()->lower();
    }
}
