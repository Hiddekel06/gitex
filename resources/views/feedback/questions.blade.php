@extends('layouts.app')

@section('content')
<style>
    .feedback-container {
        max-width: 980px;
    }

    .feedback-card {
        border: 1px solid #2c2c2c;
        border-radius: 22px;
        background: rgba(24, 24, 24, 0.9);
        box-shadow: 0 18px 40px -18px rgba(0, 0, 0, 0.55);
    }

    .feedback-title {
        font-weight: 700;
        letter-spacing: -0.3px;
        color: #e9f5e9;
    }

    .feedback-subtitle {
        color: #9fb99a;
    }

    .identity-box {
        border: 1px solid #2f2f2f;
        background: rgba(34, 34, 34, 0.85);
        border-radius: 16px;
    }

    .section-block {
        border-top: 1px solid #2e2e2e;
        padding-top: 1.25rem;
        margin-top: 1.25rem;
    }

    .section-title {
        color: #b8e0b2;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .question-card {
        border: 1px solid #2e2e2e;
        border-radius: 16px;
        background: #1d1d1d;
        padding: 1rem;
    }

    .question-label {
        color: #f0f0f0;
        font-weight: 600;
        margin-bottom: 0.65rem;
    }

    .form-control,
    .form-select {
        background: #181818;
        border: 1px solid #343434;
        color: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        background: #1f1f1f;
        color: #fff;
        border-color: #5a8f4c;
        box-shadow: 0 0 0 0.2rem rgba(90, 143, 76, 0.2);
    }

    .radio-inline {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-right: 1rem;
    }

    .coming-next {
        border: 1px dashed #4a4a4a;
        border-radius: 14px;
        background: rgba(40, 40, 40, 0.65);
        color: #d2d2d2;
    }
</style>

<div class="container feedback-container py-4 py-md-5">
    <div class="feedback-card p-4 p-md-5">
        <h2 class="feedback-title mb-2">{{ $questionnaire->titre }}</h2>
        @if($questionnaire->description)
            <p class="feedback-subtitle mb-4">{{ $questionnaire->description }}</p>
        @endif

        <div class="identity-box p-3 mb-4">
            <div><strong>Membre:</strong> {{ $identification['name'] }}</div>
            <div><strong>Email:</strong> {{ $identification['email'] }}</div>
            <div><strong>Equipe:</strong> {{ $equipeNom ?: 'N/A' }}</div>
        </div>

        <form>
            @php $globalIndex = 1; @endphp

            @foreach($questionsBySection as $section => $sectionQuestions)
                <div class="section-block">
                    <div class="section-title mb-3">{{ $section }}</div>

                    <div class="d-grid gap-3">
                        @foreach($sectionQuestions as $question)
                            <div class="question-card">
                                <div class="question-label">
                                    {{ $globalIndex }}. {{ $question->intitule }}
                                    @if($question->is_required)
                                        <span style="color:#ff8f8f;">*</span>
                                    @endif
                                </div>

                                @if($question->type_reponse === 'rating_1_5')
                                    <select class="form-select" name="reponse[{{ $question->id }}]" {{ $question->is_required ? 'required' : '' }}>
                                        <option value="">Selectionner une note</option>
                                        @php
                                            $options = $question->options_json;
                                            if (!is_array($options) || empty($options)) {
                                                $options = [1, 2, 3, 4, 5];
                                            }
                                        @endphp
                                        @foreach($options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif($question->type_reponse === 'yes_no')
                                    <label class="radio-inline">
                                        <input type="radio" name="reponse[{{ $question->id }}]" value="oui" {{ $question->is_required ? 'required' : '' }}>
                                        <span>Oui</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="reponse[{{ $question->id }}]" value="non" {{ $question->is_required ? 'required' : '' }}>
                                        <span>Non</span>
                                    </label>
                                @elseif($question->type_reponse === 'long_text')
                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        name="reponse[{{ $question->id }}]"
                                        placeholder="Votre reponse"
                                        {{ $question->is_required ? 'required' : '' }}></textarea>
                                @elseif($question->type_reponse === 'int')
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="int_value[{{ $question->id }}]"
                                        min="0"
                                        step="1"
                                        {{ $question->is_required ? 'required' : '' }}>
                                @else
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="reponse[{{ $question->id }}]"
                                        placeholder="Votre reponse"
                                        {{ $question->is_required ? 'required' : '' }}>
                                @endif
                            </div>
                            @php $globalIndex++; @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach
        </form>

        <div class="coming-next p-3 mt-4">
            Affichage termine: la sauvegarde des reponses sera branchee a l'etape suivante.
        </div>
    </div>
</div>
@endsection
