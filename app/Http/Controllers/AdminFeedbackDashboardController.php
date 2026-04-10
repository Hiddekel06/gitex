<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\FeedbackSubmission;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\ReponseUtilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminFeedbackDashboardController extends Controller
{
    public function index()
    {
        $questionnaire = Questionnaire::query()
            ->where('code', 'gitex_feedback_2026')
            ->with('questions')
            ->first();

        if (!$questionnaire) {
            return view('admin.feedback-dashboard', [
                'questionnaire' => null,
                'kpi' => [
                    'totalSubmissions' => 0,
                    'uniqueTeams' => 0,
                    'avgCompletionRate' => 0,
                    'avgGlobalRating' => 0,
                    'avgOrganizationRating' => 0,
                ],
                'charts' => [
                    'teams' => ['labels' => [], 'values' => []],
                    'contacts' => ['oui' => 0, 'non' => 0],
                    'recommendation' => ['oui' => 0, 'non' => 0],
                    'submissionsByDate' => ['labels' => [], 'values' => []],
                ],
                'latestSubmissions' => collect(),
            ]);
        }

        $questions = $questionnaire->questions;
        $questionIds = $questions->pluck('id');

        $submissions = FeedbackSubmission::query()
            ->where('questionnaire_id', $questionnaire->id)
            ->with('user.equipe')
            ->orderByDesc('submitted_at')
            ->get();

        $userIds = $submissions->pluck('user_id')->filter()->unique()->values();

        $responses = ReponseUtilisateur::query()
            ->whereIn('question_id', $questionIds)
            ->whereIn('user_id', $userIds)
            ->get();

        $requiredCount = $questions->where('is_required', true)->count();

        $completionRates = $submissions->map(function ($submission) use ($responses, $requiredCount) {
            if ($requiredCount === 0) {
                return 100;
            }

            $answeredCount = $responses
                ->where('user_id', $submission->user_id)
                ->pluck('question_id')
                ->unique()
                ->count();

            return min(100, round(($answeredCount / $requiredCount) * 100, 1));
        });

        $avgCompletionRate = $completionRates->count() > 0
            ? round($completionRates->avg(), 1)
            : 0;

        $globalRatingQuestion = $this->findQuestionByNeedle($questions, 'participation globale');
        $organizationRatingQuestion = $this->findQuestionByNeedle($questions, 'organisation du deplacement');
        $contactsQuestion = $this->findQuestionByNeedle($questions, 'contacts interessants');
        $recommendationQuestion = $this->findQuestionByNeedle($questions, 'recommanderiez-vous ce type');

        $avgGlobalRating = $this->averageNumericResponse($responses, $globalRatingQuestion?->id);
        $avgOrganizationRating = $this->averageNumericResponse($responses, $organizationRatingQuestion?->id);

        $teamStats = $submissions
            ->groupBy(fn ($submission) => optional(optional($submission->user)->equipe)->nom ?: 'Equipe inconnue')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        $contactsStats = $this->yesNoStats($responses, $contactsQuestion?->id);
        $recommendationStats = $this->yesNoStats($responses, $recommendationQuestion?->id);

        $submissionsByDate = $submissions
            ->groupBy(function ($submission) {
                $date = $submission->submitted_at ?? $submission->created_at;
                return $date ? $date->format('Y-m-d') : 'N/A';
            })
            ->map(fn ($group) => $group->count())
            ->sortKeys();

        $latestSubmissions = $submissions->take(10)->map(function ($submission) {
            return [
                'member' => optional($submission->user)->name ?: 'N/A',
                'email' => $submission->email,
                'team' => optional(optional($submission->user)->equipe)->nom ?: 'N/A',
                'submitted_at' => ($submission->submitted_at ?? $submission->created_at)?->format('d/m/Y H:i') ?: '-',
            ];
        });

        return view('admin.feedback-dashboard', [
            'questionnaire' => $questionnaire,
            'kpi' => [
                'totalSubmissions' => $submissions->count(),
                'uniqueTeams' => $submissions->pluck('equipe_id')->filter()->unique()->count(),
                'avgCompletionRate' => $avgCompletionRate,
                'avgGlobalRating' => $avgGlobalRating,
                'avgOrganizationRating' => $avgOrganizationRating,
            ],
            'charts' => [
                'teams' => [
                    'labels' => $teamStats->keys()->values(),
                    'values' => $teamStats->values()->values(),
                ],
                'contacts' => $contactsStats,
                'recommendation' => $recommendationStats,
                'submissionsByDate' => [
                    'labels' => $submissionsByDate->keys()->values(),
                    'values' => $submissionsByDate->values()->values(),
                ],
            ],
            'latestSubmissions' => $latestSubmissions,
        ]);
    }

    private function findQuestionByNeedle($questions, string $needle): ?Question
    {
        $normalizedNeedle = $this->normalize($needle);

        return $questions->first(function ($question) use ($normalizedNeedle) {
            return str_contains($this->normalize($question->intitule), $normalizedNeedle);
        });
    }

    private function averageNumericResponse($responses, ?int $questionId): float
    {
        if (!$questionId) {
            return 0;
        }

        $values = $responses
            ->where('question_id', $questionId)
            ->map(function ($response) {
                if ($response->int_value !== null) {
                    return (float) $response->int_value;
                }

                return is_numeric($response->reponse) ? (float) $response->reponse : null;
            })
            ->filter(fn ($value) => $value !== null)
            ->values();

        return $values->count() > 0 ? round($values->avg(), 2) : 0;
    }

    private function yesNoStats($responses, ?int $questionId): array
    {
        if (!$questionId) {
            return ['oui' => 0, 'non' => 0];
        }

        $normalized = $responses
            ->where('question_id', $questionId)
            ->map(fn ($response) => strtolower(trim((string) $response->reponse)));

        return [
            'oui' => $normalized->filter(fn ($value) => $value === 'oui')->count(),
            'non' => $normalized->filter(fn ($value) => $value === 'non')->count(),
        ];
    }

    private function normalize(string $value): string
    {
        return (string) Str::of($value)->ascii()->lower();
    }

    public function detailedResponses(Request $request)
    {
        $questionnaire = Questionnaire::query()
            ->where('code', 'gitex_feedback_2026')
            ->with(['questions' => function ($query) {
                $query->orderBy('ordre');
            }])
            ->first();

        $equipes = Equipe::query()->orderBy('nom')->get();
        $selectedEquipeId = $request->integer('equipe_id');

        if (!$questionnaire) {
            return view('admin.feedback-responses', [
                'questionnaire' => null,
                'equipes' => $equipes,
                'selectedEquipeId' => $selectedEquipeId,
                'rows' => collect(),
            ]);
        }

        $questions = $questionnaire->questions;

        $submissionsQuery = FeedbackSubmission::query()
            ->where('questionnaire_id', $questionnaire->id)
            ->with('user.equipe')
            ->orderByDesc('submitted_at');

        if ($selectedEquipeId) {
            $submissionsQuery->where('equipe_id', $selectedEquipeId);
        }

        $submissions = $submissionsQuery->get();
        $userIds = $submissions->pluck('user_id')->filter()->unique()->values();
        $questionIds = $questions->pluck('id');

        $responses = ReponseUtilisateur::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('question_id', $questionIds)
            ->get()
            ->keyBy(function ($response) {
                return $response->user_id . '_' . $response->question_id;
            });

        $rows = collect();

        foreach ($submissions as $submission) {
            $user = $submission->user;
            $teamName = optional($user?->equipe)->nom ?: 'N/A';
            $submittedAt = ($submission->submitted_at ?? $submission->created_at)?->format('d/m/Y H:i') ?: '-';

            foreach ($questions as $question) {
                $response = $responses->get($submission->user_id . '_' . $question->id);

                $rows->push([
                    'date' => $submittedAt,
                    'equipe' => $teamName,
                    'member' => $user?->name ?: 'N/A',
                    'email' => $submission->email,
                    'section' => $question->section ?: 'Questions',
                    'question' => $question->intitule,
                    'reponse' => $response?->reponse ?: '-',
                    'justification' => $response?->justification ?: '-',
                    'int_value' => $response?->int_value ?? '-',
                ]);
            }
        }

        return view('admin.feedback-responses', [
            'questionnaire' => $questionnaire,
            'equipes' => $equipes,
            'selectedEquipeId' => $selectedEquipeId,
            'rows' => $rows,
        ]);
    }
}
