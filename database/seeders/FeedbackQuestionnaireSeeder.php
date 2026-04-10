<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class FeedbackQuestionnaireSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $questionnaire = Questionnaire::updateOrCreate(
            ['code' => 'gitex_feedback_2026'],
            [
                'titre' => 'Feedback GITEX Africa 2026',
                'description' => 'Retour d\'expérience sur le GITEX 2026',
                'is_active' => true,
                'version' => 1,
            ]
        );

        $questions = [
            [
                'section' => 'Expérience globale',
                'intitule' => 'Comment évaluez-vous votre participation globale au GITEX Africa ?',
                'type_question' => 'direct',
                'type_reponse' => 'rating_1_5',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => [1, 2, 3, 4, 5],
            ],
            [
                'section' => 'Expérience globale',
                'intitule' => 'Quels ont été les moments les plus marquants pour votre équipe ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Apports et apprentissages',
                'intitule' => 'Qu\'avez-vous appris durant ces 3 jours ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Apports et apprentissages',
                'intitule' => 'Cette expérience va-t-elle contribuer à faire évoluer votre projet ? Si oui, comment ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Opportunités et réseau',
                'intitule' => 'Avez-vous pu établir des contacts intéressants (partenaires, investisseurs, mentors) ?',
                'type_question' => 'binaire',
                'type_reponse' => 'yes_no',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => ['oui', 'non'],
            ],
            [
                'section' => 'Opportunités et réseau',
                'intitule' => 'Ces connexions ont-elles un potentiel concret pour la suite de votre projet ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Comment évaluez-vous l\'organisation du déplacement et de l\'accompagnement ?',
                'type_question' => 'direct',
                'type_reponse' => 'rating_1_5',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => [1, 2, 3, 4, 5],
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Qu\'est-ce qui a bien fonctionné ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Qu\'est-ce qui pourrait être amélioré ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Impact du Gov\'athon',
                'intitule' => 'En quoi le Gov\'athon vous a-t-il aidé à tirer profit de cette expérience ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Impact du Gov\'athon',
                'intitule' => 'Recommanderiez-vous ce type d\'accompagnement à d\'autres équipes ? Pourquoi ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Suite et perspectives',
                'intitule' => 'Quelles sont vos prochaines étapes après cet événement ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Feedback libre',
                'intitule' => 'Avez-vous des suggestions ou remarques supplémentaires ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => false,
                'has_justification' => false,
                'options_json' => null,
            ],
        ];

        foreach ($questions as $index => $questionData) {
            Question::updateOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'ordre' => $index + 1,
                ],
                [
                    'intitule' => $questionData['intitule'],
                    'section' => $questionData['section'],
                    'type_question' => $questionData['type_question'],
                    'type_reponse' => $questionData['type_reponse'],
                    'is_required' => $questionData['is_required'],
                    'has_justification' => $questionData['has_justification'],
                    'options_json' => $questionData['options_json'],
                ]
            );
        }
    }
}
