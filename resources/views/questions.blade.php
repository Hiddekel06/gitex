@extends('layouts.app')

@section('content')
<style>
    .question-card {
        background: #181818;
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .form-label { color: #e9f5e9; }
    .form-check-label { color: #fff; }
    .form-control, .form-select {
        background: #222;
        color: #fff;
        border: 1px solid #333;
    }
    .form-control:focus, .form-select:focus {
        background: #222;
        color: #fff;
        border-color: #5a8f4c;
        box-shadow: 0 0 0 3px rgba(90, 143, 76, 0.15);
    }
    .btn-modern {
        background-color: #5a8f4c;
        border: none;
        border-radius: 40px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        color: white;
        transition: background-color 0.2s, transform 0.1s;
        width: 100%;
        letter-spacing: 0.3px;
    }
    .btn-modern:hover { background-color: #457a37; }
</style>
<div class="container py-5">
    <form method="POST" action="{{ route('questions.store') }}">
        @csrf
        @foreach($questions as $i => $question)
            <div class="question-card mb-4">
                <label class="form-label">{{ ($i+1) . '. ' . $question->intitule }}</label>
                <div class="form-check form-check-inline ms-2">
                    <input class="form-check-input" type="radio" name="reponse[{{ $question->id }}]" id="q{{ $question->id }}_oui" value="oui" required>
                    <label class="form-check-label" for="q{{ $question->id }}_oui">Oui</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reponse[{{ $question->id }}]" id="q{{ $question->id }}_non" value="non" required>
                    <label class="form-check-label" for="q{{ $question->id }}_non">Non</label>
                </div>
                <div class="mt-3" id="justif_block_{{ $question->id }}" style="display:none;">
                    <label for="justification_{{ $question->id }}" class="form-label">Justification (obligatoire si non)</label>
                    <input type="text" class="form-control" name="justification[{{ $question->id }}]" id="justification_{{ $question->id }}">
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn-modern mt-3">Valider</button>
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
    });
</script>
@endsection
