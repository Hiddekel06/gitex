<?php

namespace App\Exports;

use App\Models\Equipe;
use App\Models\Question;
use App\Models\ReponseUtilisateur;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EquipesExport implements FromArray, WithHeadings
{
    protected $equipes;
    protected $questions;
    protected $reponses;

    public function __construct($equipes, $questions, $reponses)
    {
        $this->equipes = $equipes;
        $this->questions = $questions;
        $this->reponses = $reponses;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->equipes as $equipe) {
            $row = [
                'Equipe' => $equipe->nom,
            ];
            foreach ($this->questions as $question) {
                $cell = [];
                foreach ($equipe->users as $user) {
                    $rep = $this->reponses->where('user_id', $user->id)->where('question_id', $question->id)->first();
                    $txt = $rep ? $rep->reponse : '-';
                    if ($rep && $rep->justification) {
                        $txt .= ' (Justif: ' . $rep->justification . ')';
                    }
                    if ($rep && $rep->int_value !== null) {
                        $txt .= ' [Num: ' . $rep->int_value . ']';
                    }
                    $cell[] = $txt;
                }
                $row[$question->intitule] = implode(" | ", $cell);
            }
            $rows[] = $row;
        }
        return $rows;
    }

    public function headings(): array
    {
        $headings = ['Equipe'];
        foreach ($this->questions as $question) {
            $headings[] = $question->intitule;
        }
        return $headings;
    }
}
