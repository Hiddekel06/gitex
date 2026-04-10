<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'intitule',
        'section',
        'ordre',
        'is_required',
        'options_json',
        'type_reponse',
        'has_justification',
        'type_question',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'has_justification' => 'boolean',
        'options_json' => 'array',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
