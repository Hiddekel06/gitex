@extends('layouts.app')

@section('content')
<style>
    body {
        background: #0f0f0f;
    }

    .question-card {
        background: linear-gradient(145deg, #1a1a1a, #121212);
        color: #fff;
        border-radius: 16px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid #2a2a2a;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .question-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }

    .question-title {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 10px;
        display: block;
    }

    .form-control, .form-select {
        background: #1e1e1e;
        color: #fff;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 10px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6abf69;
        box-shadow: 0 0 0 2px rgba(106,191,105,0.2);
    }

    .form-check {
        background: #1e1e1e;
        padding: 8px 12px;
        border-radius: 10px;
        margin-right: 10px;
        border: 1px solid #2a2a2a;
        transition: 0.2s;
    }

    .form-check:hover {
        background: #262626;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-label {
        margin-left: 5px;
        cursor: pointer;
    }

    .btn-modern {
        background: linear-gradient(135deg, #5a8f4c, #6abf69);
        border: none;
        border-radius: 50px;
        padding: 0.9rem;
        font-weight: 600;
        color: white;
        width: 100%;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .btn-modern:hover {
        transform: scale(1.02);
        background: linear-gradient(135deg, #4d7d40, #5fae5d);
    }

    .container {
        max-width: 700px;
    }

    .divider {
        height: 1px;
        background: #2a2a2a;
        margin: 15px 0;
    }

    .input-label {
        font-size: 0.85rem;
        color: #9ee29e;
        margin-bottom: 5px;
    }
</style>

<div class="container py-5">
    <form method="POST" action="{{ route('questions.store') }}">
        @csrf

        @foreach($questions as $i => $question)
            <div class="question-card" id="question_card_{{ $i }}">

                <span class="question-title">
                    {{ ($i+1) . '. ' . $question->intitule }}
                </span>

                @if($question->type_reponse === 'int' && !$question->has_justification)
                    <input type="number" class="form-control mt-2"
                        name="reponse[{{ $question->id }}]"
                        id="reponse_{{ $question->id }}"
                        min="0" step="1" required>

                @elseif($question->type_reponse === 'text' && !$question->has_justification)
                    <input type="text" class="form-control mt-2"
                        name="reponse[{{ $question->id }}]"
                        id="reponse_{{ $question->id }}" required>

                @else
                    <div class="d-flex mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="reponse[{{ $question->id }}]"
                                id="q{{ $question->id }}_oui"
                                value="oui" required>
                            <label class="form-check-label" for="q{{ $question->id }}_oui">Oui</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="reponse[{{ $question->id }}]"
                                id="q{{ $question->id }}_non"
                                value="non" required>
                            <label class="form-check-label" for="q{{ $question->id }}_non">Non</label>
                        </div>
                    </div>

                    @if($question->has_justification)
                    <div class="mt-3" id="justif_block_{{ $question->id }}" style="display:none;">
                        <div class="input-label">Justification (obligatoire si NON)</div>
                        <input type="text" class="form-control"
                            name="justification[{{ $question->id }}]"
                            id="justification_{{ $question->id }}">
                    </div>
                    @endif

                    @if($question->type_reponse === 'int')
                    <div class="mt-3" id="int_block_{{ $question->id }}" style="display:none;">
                        <div class="input-label">Veuillez indiquer le poids</div>
                        <input type="number" class="form-control"
                            name="int_value[{{ $question->id }}]"
                            id="int_value_{{ $question->id }}"
                            min="0" step="1">
                    </div>
                    @endif

                @endif

            </div>
        @endforeach

        <button type="submit" class="btn-modern mt-4">
            Valider le formulaire
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($questions as $question)
            const ouiRadio{{ $question->id }} = document.getElementById('q{{ $question->id }}_oui');
            const nonRadio{{ $question->id }} = document.getElementById('q{{ $question->id }}_non');
            const justifBlock{{ $question->id }} = document.getElementById('justif_block_{{ $question->id }}');
            const justifInput{{ $question->id }} = document.getElementById('justification_{{ $question->id }}');

            if (nonRadio{{ $question->id }}) {
                nonRadio{{ $question->id }}.addEventListener('change', function() {
                    justifBlock{{ $question->id }}.style.display = 'block';
                    justifInput{{ $question->id }}.setAttribute('required', 'required');
                });
            }

            if (ouiRadio{{ $question->id }}) {
                ouiRadio{{ $question->id }}.addEventListener('change', function() {
                    justifBlock{{ $question->id }}.style.display = 'none';
                    justifInput{{ $question->id }}.removeAttribute('required');
                });
            }
        @endforeach

        @foreach($questions as $question)
            @if($question->type_reponse === 'int')
                const ouiBtn{{ $question->id }} = document.getElementById('q{{ $question->id }}_oui');
                const nonBtn{{ $question->id }} = document.getElementById('q{{ $question->id }}_non');
                const intBlock{{ $question->id }} = document.getElementById('int_block_{{ $question->id }}');

                if (ouiBtn{{ $question->id }} && intBlock{{ $question->id }}) {
                    ouiBtn{{ $question->id }}.addEventListener('change', function() {
                        if (ouiBtn{{ $question->id }}.checked) {
                            intBlock{{ $question->id }}.style.display = 'block';
                        }
                    });
                }

                if (nonBtn{{ $question->id }} && intBlock{{ $question->id }}) {
                    nonBtn{{ $question->id }}.addEventListener('change', function() {
                        if (nonBtn{{ $question->id }}.checked) {
                            intBlock{{ $question->id }}.style.display = 'none';
                        }
                    });
                }
            @endif
        @endforeach
    });
</script>
@endsection