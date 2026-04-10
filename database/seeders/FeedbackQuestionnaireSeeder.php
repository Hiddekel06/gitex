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
                'description' => 'Retour d\'experience sur le Gitex2026',
                'is_active' => true,
                'version' => 1,
            ]
        );

        $questions = [
            [
                'section' => 'Experience globale',
                'intitule' => 'Comment evaluez-vous votre participation globale au GITEX Africa ?',
                'type_question' => 'direct',
                'type_reponse' => 'rating_1_5',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => [1, 2, 3, 4, 5],
            ],
            [
                'section' => 'Experience globale',
                'intitule' => 'Quels ont ete les moments les plus marquants pour votre equipe ?',
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
                'intitule' => 'Cette experience va-t-elle contribuer a faire evoluer votre projet ? Si oui, comment ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Opportunites et reseau',
                'intitule' => 'Avez-vous pu etablir des contacts interessants (partenaires, investisseurs, mentors) ?',
                'type_question' => 'binaire',
                'type_reponse' => 'yes_no',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => ['oui', 'non'],
            ],
            [
                'section' => 'Opportunites et reseau',
                'intitule' => 'Ces connexions ont-elles un potentiel concret pour la suite de votre projet ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Comment evaluez-vous l\'organisation du deplacement et de l\'accompagnement ?',
                'type_question' => 'direct',
                'type_reponse' => 'rating_1_5',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => [1, 2, 3, 4, 5],
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Qu\'est-ce qui a bien fonctionne ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Organisation et accompagnement',
                'intitule' => 'Qu\'est-ce qui pourrait etre ameliore ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Impact du Gov\'athon',
                'intitule' => 'En quoi le Gov\'athon vous a-t-il aide a tirer profit de cette experience ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Impact du Gov\'athon',
                'intitule' => 'Recommanderiez-vous ce type d\'accompagnement a d\'autres equipes ? Pourquoi ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Suite et perspectives',
                'intitule' => 'Quelles sont vos prochaines etapes apres cet evenement ?',
                'type_question' => 'direct',
                'type_reponse' => 'long_text',
                'is_required' => true,
                'has_justification' => false,
                'options_json' => null,
            ],
            [
                'section' => 'Feedback libre',
                'intitule' => 'Avez-vous des suggestions ou remarques supplementaires ?',
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
