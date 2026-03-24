@extends('layouts.app')

@section('content')
<style>
    /* ========== DESIGN SYSTEM ========== */
    :root {
        --bg-dark: #0a0a0a;
        --card-bg: rgba(18, 18, 18, 0.9);
        --border-subtle: rgba(255, 255, 255, 0.05);
        --border-glow: rgba(106, 191, 105, 0.4);
        --green-primary: #5a8f4c;
        --green-light: #7fb074;
        --green-glow: rgba(90, 143, 76, 0.3);
        --text-muted: #aaa;
        --transition-smooth: all 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
    }

    /* Glassmorphic container */
    .container {
        max-width: 760px;
        position: relative;
        z-index: 2;
    }

    /* Progress Bar */
    .progress-tracker {
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        border-radius: 100px;
        padding: 0.5rem 1rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .progress-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .progress-bar-container {
        flex: 1;
        height: 6px;
        background: #2a2a2a;
        border-radius: 3px;
        overflow: hidden;
    }
    .progress-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, var(--green-primary), var(--green-light));
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    .progress-percent {
        font-weight: 600;
        color: var(--green-light);
        min-width: 45px;
        text-align: right;
    }

    /* Question Card */
    .question-card {
        background: var(--card-bg);
        backdrop-filter: blur(2px);
        border-radius: 28px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-subtle);
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }
    .question-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 0% 0%, rgba(90, 143, 76, 0.03), transparent);
        pointer-events: none;
    }
    .question-card:hover {
        transform: translateY(-4px);
        border-color: var(--green-glow);
        box-shadow: 0 20px 30px -15px rgba(0,0,0,0.5);
    }

    .question-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .question-number {
        background: var(--green-primary);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 30px;
    }

    /* Inputs stylisés */
    .form-control, .form-select {
        background: #1a1a1a;
        color: #fff;
        border: 1px solid #2a2a2a;
        border-radius: 20px;
        padding: 0.75rem 1.2rem;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
    }
    .disabled-field {
        background: #232323 !important;
        color: #888 !important;
        border-color: #444 !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
        pointer-events: none !important;
    }
    input[type="number"].form-control,
    input[type="text"].form-control {
        color: #fff !important;
        /* Pour forcer le blanc même si le navigateur applique une couleur par défaut */
        caret-color: #fff;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 3px var(--green-glow);
        background: #202020;
        outline: none;
    }

    /* Radio buttons modern style */
    .radio-group {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    .radio-custom {
        position: relative;
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 40px;
        padding: 0.5rem 1.2rem;
        cursor: pointer;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .radio-custom:hover {
        background: #262626;
        border-color: var(--green-primary);
    }
    .radio-custom input[type="radio"] {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #6c6c6c;
        border-radius: 50%;
        margin: 0;
        position: relative;
        cursor: pointer;
        transition: 0.1s;
    }
    .radio-custom input[type="radio"]:checked {
        border-color: var(--green-primary);
        background-color: var(--green-primary);
        box-shadow: inset 0 0 0 4px #1a1a1a;
    }
    .radio-custom label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    /* Dynamic blocks (sans animation) */
    .dynamic-block {
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        background: rgba(0,0,0,0.3);
        border-radius: 20px;
        border-left: 3px solid var(--green-primary);
        /* plus d'animation pour ouverture instantanée */
    }

    /* Bouton principal */
    .btn-modern {
        background: linear-gradient(135deg, var(--green-primary), #3c6e2f);
        border: none;
        border-radius: 50px;
        padding: 1rem;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        width: 100%;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .btn-modern:hover {
        transform: scale(1.02);
        filter: brightness(1.05);
        box-shadow: 0 10px 25px -8px var(--green-glow);
    }
    .btn-modern:active {
        transform: scale(0.98);
    }
    .btn-modern:disabled {
        opacity: 0.6;
        transform: none;
        cursor: not-allowed;
    }

    /* Tooltip / input hint */
    .input-hint {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        margin-left: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .question-card {
            padding: 1.2rem;
        }
        .radio-group {
            flex-direction: column;
            gap: 0.75rem;
        }
        .radio-custom {
            justify-content: center;
        }
        .progress-tracker {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
    }
</style>

<div class="container py-4 py-md-5">
    <!-- En‑tête avec progression -->
    <div class="progress-tracker">
        <div class="progress-info">
            <span style="display: flex; align-items: center; gap: 0.4em;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;"><rect x="4" y="3.5" width="12" height="15" rx="3" fill="#ffe066" stroke="#5a8f4c" stroke-width="1.2"/><rect x="7" y="2" width="6" height="3" rx="1.2" fill="#fff7b2" stroke="#5a8f4c" stroke-width="1.1"/></svg>
                Questionnaire
            </span>
            <span class="badge bg-dark text-light" id="answeredCount">0</span>
            <span>répondues / {{ count($questions) }}</span>
        </div>
        <div class="progress-bar-container">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-percent" id="progressPercent">0%</div>
    </div>

    <form method="POST" action="{{ route('questions.store') }}" id="questionForm">
        @csrf

        @foreach($questions as $i => $question)
            <div class="question-card" data-question-id="{{ $question->id }}" data-question-type="{{ $question->type_question }}" data-rep-type="{{ $question->type_reponse }}">
                <div class="question-title">
                    <span class="question-number">{{ $i+1 }}</span>
                    <span>{{ $question->intitule }}</span>
                </div>

                @if($question->type_question === 'direct')
                    @if($question->type_reponse === 'int' && $i === 1)
                        <div class="radio-group">
                            <div class="radio-custom">
                                <input type="radio" class="radio-option" name="int_value[{{ $question->id }}]" id="int_value_{{ $question->id }}_2" value="2" required>
                                <label for="int_value_{{ $question->id }}_2">2 membres</label>
                            </div>
                            <div class="radio-custom">
                                <input type="radio" class="radio-option" name="int_value[{{ $question->id }}]" id="int_value_{{ $question->id }}_3" value="3" required>
                                <label for="int_value_{{ $question->id }}_3">3 membres</label>
                            </div>
                        </div>
                        <div class="input-hint">Choisissez 2 ou 3 membres</div>
                    @elseif($question->type_reponse === 'int')
                        <input type="number" class="form-control mt-2 question-input"
                            name="int_value[{{ $question->id }}]"
                            id="int_value_{{ $question->id }}"
                            min="0" step="1" required
                            data-question-id="{{ $question->id }}">
                        <div class="input-hint">Entrez une valeur numérique</div>
                    @elseif($question->type_reponse === 'text')
                        <input type="text" class="form-control mt-2 question-input"
                            name="reponse[{{ $question->id }}]"
                            id="reponse_{{ $question->id }}"
                            required data-question-id="{{ $question->id }}">
                        <div class="input-hint">Réponse textuelle</div>
                    @endif
                @else
                    @if($i === 2)
                        <div class="radio-group">
                            <div class="radio-custom">
                                <input type="radio" class="radio-option"
                                    name="reponse[{{ $question->id }}]"
                                    id="q{{ $question->id }}_oui"
                                    value="oui"
                                    data-question-id="{{ $question->id }}"
                                    required>
                                <label for="q{{ $question->id }}_oui">Oui</label>
                            </div>
                            <div class="radio-custom">
                                <input type="radio" class="radio-option"
                                    name="reponse[{{ $question->id }}]"
                                    id="q{{ $question->id }}_non"
                                    value="non"
                                    data-question-id="{{ $question->id }}"
                                    required>
                                <label for="q{{ $question->id }}_non">Non</label>
                            </div>
                            <div class="radio-custom">
                                <input type="radio" class="radio-option"
                                    name="reponse[{{ $question->id }}]"
                                    id="q{{ $question->id }}_en_cours"
                                    value="en_cours"
                                    data-question-id="{{ $question->id }}"
                                    required>
                                <label for="q{{ $question->id }}_en_cours">En cours</label>
                            </div>
                        </div>
                    @else
                        <div class="radio-group">
                            <div class="radio-custom">
                                <input type="radio" class="radio-option"
                                    name="reponse[{{ $question->id }}]"
                                    id="q{{ $question->id }}_oui"
                                    value="oui"
                                    data-question-id="{{ $question->id }}"
                                    required>
                                <label for="q{{ $question->id }}_oui">Oui</label>
                            </div>
                            <div class="radio-custom">
                                <input type="radio" class="radio-option"
                                    name="reponse[{{ $question->id }}]"
                                    id="q{{ $question->id }}_non"
                                    value="non"
                                    data-question-id="{{ $question->id }}"
                                    required>
                                <label for="q{{ $question->id }}_non">Non</label>
                            </div>
                        </div>
                    @endif

                    @if($question->has_justification)
                    <div class="dynamic-block" id="justif_block_{{ $question->id }}" style="display:none;">
                        <label class="form-label">Quelle est la raison ?</label>
                        <input type="text" class="form-control"
                            name="justification[{{ $question->id }}]"
                            id="justification_{{ $question->id }}">
                    </div>
                    @endif

                    @if($question->type_reponse === 'int')
                    <div class="dynamic-block" id="int_block_{{ $question->id }}" style="display:none;">
                        <label class="form-label">Indiquez le poids en Kg</label>
                        <input type="number" class="form-control"
                            name="int_value[{{ $question->id }}]"
                            id="int_value_{{ $question->id }}"
                            min="0" step="1">
                    </div>
                    @endif
                @endif
            </div>
        @endforeach

        <button type="submit" class="btn-modern mt-4" id="submitBtn">
            Valider le formulaire
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('questionForm');
    const allCards = document.querySelectorAll('.question-card');
    const submitBtn = document.getElementById('submitBtn');

    if (!allCards.length) return;

    // =========================
    // 🔹 QUESTION 1 (LOGIQUE PRINCIPALE)
    // =========================
    const firstCard = allCards[0];
    const firstQuestionId = firstCard.dataset.questionId;

    const firstRadios = document.querySelectorAll(
        `input[name="reponse[${firstQuestionId}]"]`
    );

    function disableOtherQuestions(disable = true) {
        allCards.forEach((card, index) => {
            if (index === 0) return;

            const inputs = card.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                if (disable) {
                    input.removeAttribute('required');
                    input.checked = false;
                    input.value = '';
                }
            });
        });
    }

    function handleFirstQuestion() {
        let selected = null;

        firstRadios.forEach(r => {
            if (r.checked) selected = r.value;
        });

        if (selected === 'non') {
            // 🔴 MASQUER toutes les autres questions
            allCards.forEach((card, index) => {
                card.style.display = (index === 0) ? '' : 'none';
            });

            disableOtherQuestions(true);

            submitBtn.innerText = "Valider et terminer";
        } else {
            // 🟢 AFFICHER toutes les questions
            allCards.forEach(card => {
                card.style.display = '';
            });

            submitBtn.innerText = "Valider le formulaire";
        }

        updateProgress();
    }

    firstRadios.forEach(r => {
        r.addEventListener('change', handleFirstQuestion);
    });

    // =========================
    // 🔹 JUSTIFICATION + INT
    // =========================
    allCards.forEach((card) => {

        const qid = card.dataset.questionId;

        const oui = document.getElementById(`q${qid}_oui`);
        const non = document.getElementById(`q${qid}_non`);

        const justifBlock = document.getElementById(`justif_block_${qid}`);
        const justifInput = document.getElementById(`justification_${qid}`);

        const intBlock = document.getElementById(`int_block_${qid}`);

        function updateDynamicFields() {

            if (card.style.display === 'none') return;

            // JUSTIFICATION
            if (justifBlock && justifInput) {
                if (non && non.checked) {
                    justifBlock.style.display = 'block';
                    justifInput.setAttribute('required', 'required');
                } else {
                    justifBlock.style.display = 'none';
                    justifInput.removeAttribute('required');
                }
            }

            // INT
            if (intBlock) {
                if (oui && oui.checked) {
                    intBlock.style.display = 'block';
                } else {
                    intBlock.style.display = 'none';
                }
            }
        }

        if (oui) oui.addEventListener('change', updateDynamicFields);
        if (non) non.addEventListener('change', updateDynamicFields);

        updateDynamicFields();
    });

    // =========================
    // 🔹 PROGRESSION
    // =========================
    function updateProgress() {
        let total = 0;
        let answered = 0;

        allCards.forEach(card => {

            if (card.style.display === 'none') return;

            total++;

            const inputs = card.querySelectorAll('input');

            let isAnswered = false;

            inputs.forEach(input => {
                if (
                    (input.type === 'radio' && input.checked) ||
                    (input.type !== 'radio' && input.value.trim() !== '')
                ) {
                    isAnswered = true;
                }
            });

            if (isAnswered) answered++;
        });

        const percent = total === 0 ? 0 : Math.round((answered / total) * 100);

        document.getElementById('answeredCount').innerText = answered;
        document.getElementById('progressFill').style.width = percent + '%';
        document.getElementById('progressPercent').innerText = percent + '%';
    }

    form.addEventListener('change', updateProgress);
    form.addEventListener('input', updateProgress);

    // =========================
    // 🔹 SUBMIT PROTECTION
    // =========================
    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerText = "✨ Envoi en cours...";
    });

    // =========================
    // INIT
    // =========================
    handleFirstQuestion();
    updateProgress();

});
</script>

@endsection