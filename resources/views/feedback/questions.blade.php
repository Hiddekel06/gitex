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
        border-radius: 14px;
        box-shadow: 0 2px 10px -6px #000a;
        padding-right: 2.5rem;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        position: relative;
    }

    /* Flèche custom pour le select */
    .form-select {
        background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="18" viewBox="0 0 20 20" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 8.293a1 1 0 011.414 0L10 9.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }

    .form-select:focus {
        border-color: #5a8f4c;
        box-shadow: 0 0 0 3px rgba(90, 143, 76, 0.18);
    }

    /* Placeholders blancs mais plus subtils */
    .form-control::placeholder,
    .form-select::placeholder {
        color: #fff !important;
        opacity: 0.6 !important;
    }
    .form-control::-webkit-input-placeholder { color: #fff !important; opacity: 0.6 !important; }
    .form-control::-moz-placeholder { color: #fff !important; opacity: 0.6 !important; }
    .form-control:-ms-input-placeholder { color: #fff !important; opacity: 0.6 !important; }
    .form-control::-ms-input-placeholder { color: #fff !important; opacity: 0.6 !important; }

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

    .rating-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }

    .rating-pill {
        position: relative;
    }

    .rating-pill input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .rating-pill span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 40px;
        padding: 0 0.75rem;
        border-radius: 999px;
        border: 1px solid #3a3a3a;
        background: #202020;
        color: #e8e8e8;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rating-pill span:hover {
        border-color: #5a8f4c;
        background: #252525;
    }

    .rating-pill input:checked + span {
        background: linear-gradient(95deg, #5a8f4c 0%, #3f6f34 100%);
        border-color: #74a965;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(90, 143, 76, 0.2);
    }

    .coming-next {
        border: 1px dashed #4a4a4a;
        border-radius: 14px;
        background: rgba(40, 40, 40, 0.65);
        color: #d2d2d2;
    }

    .why-block {
        margin-top: 0.8rem;
        display: none;
    }
</style>

<div class="container feedback-container py-4 py-md-5">
    <div class="feedback-card p-4 p-md-5">
        <h2 class="feedback-title mb-2">{{ $questionnaire->titre }}</h2>
        @if($questionnaire->description)
            <p class="feedback-subtitle mb-4">{{ $questionnaire->description }}</p>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="identity-box p-3 mb-4">
            <div><strong>Membre:</strong> {{ $identification['name'] }}</div>
            <div><strong>Email:</strong> {{ $identification['email'] }}</div>
            <div><strong>Equipe:</strong> {{ $equipeNom ?: 'N/A' }}</div>
        </div>

        <form method="POST" action="{{ route('feedback.questions.store') }}">
            @csrf
            @php $globalIndex = 1; @endphp

            @foreach($questionsBySection as $section => $sectionQuestions)
                <div class="section-block">
                    <div class="section-title mb-3">{{ $section }}</div>

                    <div class="d-grid gap-3">
                        @foreach($sectionQuestions as $question)
                            <div class="question-card" data-question-text="{{ $question->intitule }}">
                                <div class="question-label">
                                    {{ $globalIndex }}. {{ $question->intitule }}
                                    @if($question->is_required)
                                        <span style="color:#ff8f8f;">*</span>
                                    @endif
                                </div>

                                @if(str_contains(strtolower($question->intitule), 'cette experience va-t-elle contribuer') && str_contains(strtolower($question->intitule), 'si oui, comment'))
                                    <label class="radio-inline">
                                        <input
                                            type="radio"
                                            name="reponse_choice[{{ $question->id }}]"
                                            value="oui"
                                            class="js-if-yes-toggle"
                                            data-target="if_yes_block_{{ $question->id }}"
                                            {{ old('reponse_choice.' . $question->id) === 'oui' ? 'checked' : '' }}
                                            {{ $question->is_required ? 'required' : '' }}>
                                        <span>Oui</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input
                                            type="radio"
                                            name="reponse_choice[{{ $question->id }}]"
                                            value="non"
                                            class="js-if-yes-toggle"
                                            data-target="if_yes_block_{{ $question->id }}"
                                            {{ old('reponse_choice.' . $question->id) === 'non' ? 'checked' : '' }}
                                            {{ $question->is_required ? 'required' : '' }}>
                                        <span>Non</span>
                                    </label>

                                    <div id="if_yes_block_{{ $question->id }}" class="why-block">
                                        <textarea
                                            class="form-control"
                                            rows="4"
                                            name="reponse[{{ $question->id }}]"
                                            placeholder="Comment ?"
                                            {{ $question->is_required ? 'required' : '' }}>{{ old('reponse.' . $question->id) }}</textarea>
                                    </div>
                                @elseif(str_contains(strtolower($question->intitule), 'recommanderiez-vous ce type d') && str_contains(strtolower($question->intitule), 'pourquoi'))
                                    <label class="radio-inline">
                                        <input
                                            type="radio"
                                            name="reponse_choice[{{ $question->id }}]"
                                            value="oui"
                                            class="js-why-toggle"
                                            data-target="why_block_{{ $question->id }}"
                                            {{ old('reponse_choice.' . $question->id) === 'oui' ? 'checked' : '' }}
                                            {{ $question->is_required ? 'required' : '' }}>
                                        <span>Oui</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input
                                            type="radio"
                                            name="reponse_choice[{{ $question->id }}]"
                                            value="non"
                                            class="js-why-toggle"
                                            data-target="why_block_{{ $question->id }}"
                                            {{ old('reponse_choice.' . $question->id) === 'non' ? 'checked' : '' }}
                                            {{ $question->is_required ? 'required' : '' }}>
                                        <span>Non</span>
                                    </label>

                                    <div id="why_block_{{ $question->id }}" class="why-block">
                                        <textarea
                                            class="form-control"
                                            rows="4"
                                            name="reponse[{{ $question->id }}]"
                                            placeholder="Pourquoi ?"
                                            {{ $question->is_required ? 'required' : '' }}>{{ old('reponse.' . $question->id) }}</textarea>
                                    </div>
                                @elseif($question->type_reponse === 'rating_1_5')
                                    @php
                                        $options = $question->options_json;
                                        if (!is_array($options) || empty($options)) {
                                            $options = [1, 2, 3, 4, 5];
                                        }
                                        $oldRating = old('reponse.' . $question->id);
                                    @endphp
                                    <div class="rating-group">
                                        @foreach($options as $index => $option)
                                            <label class="rating-pill">
                                                <input
                                                    type="radio"
                                                    name="reponse[{{ $question->id }}]"
                                                    value="{{ $option }}"
                                                    {{ (string) $oldRating === (string) $option ? 'checked' : '' }}
                                                    {{ $question->is_required && $index === 0 ? 'required' : '' }}>
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($question->type_reponse === 'yes_no')
                                    <label class="radio-inline">
                                        <input type="radio" name="reponse[{{ $question->id }}]" value="oui" {{ old('reponse.' . $question->id) === 'oui' ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                                        <span>Oui</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="reponse[{{ $question->id }}]" value="non" {{ old('reponse.' . $question->id) === 'non' ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                                        <span>Non</span>
                                    </label>
                                @elseif($question->type_reponse === 'long_text')
                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        name="reponse[{{ $question->id }}]"
                                        placeholder="Votre reponse"
                                        {{ $question->is_required ? 'required' : '' }}>{{ old('reponse.' . $question->id) }}</textarea>
                                @elseif($question->type_reponse === 'int')
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="int_value[{{ $question->id }}]"
                                        min="0"
                                        step="1"
                                        value="{{ old('int_value.' . $question->id) }}"
                                        {{ $question->is_required ? 'required' : '' }}>
                                @else
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="reponse[{{ $question->id }}]"
                                        placeholder="Votre reponse"
                                        value="{{ old('reponse.' . $question->id) }}"
                                        {{ $question->is_required ? 'required' : '' }}>
                                @endif
                            </div>
                            @php $globalIndex++; @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success w-100 mt-4">Envoyer mes reponses</button>
        </form>

       
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = Array.from(document.querySelectorAll('.question-card'));

    const normalize = (text) => {
        return (text || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    };

    const triggerNeedle = normalize('Avez-vous pu etablir des contacts interessants');
    const dependentNeedle = normalize('Ces connexions ont-elles un potentiel concret pour la suite de votre projet');

    const triggerCard = cards.find((card) => normalize(card.dataset.questionText).includes(triggerNeedle));
    const dependentCard = cards.find((card) => normalize(card.dataset.questionText).includes(dependentNeedle));

    const triggerYes = triggerCard ? triggerCard.querySelector('input[type="radio"][value="oui"]') : null;
    const triggerNo = triggerCard ? triggerCard.querySelector('input[type="radio"][value="non"]') : null;

    const dependentInputs = dependentCard ? Array.from(dependentCard.querySelectorAll('input, textarea, select')) : [];
    dependentInputs.forEach((input) => {
        input.dataset.originalRequired = input.required ? '1' : '0';
    });

    function setDependentVisibility(show) {
        if (!dependentCard) {
            return;
        }

        dependentCard.style.display = show ? '' : 'none';

        dependentInputs.forEach((input) => {
            if (show) {
                if (input.dataset.originalRequired === '1') {
                    input.setAttribute('required', 'required');
                }
            } else {
                input.removeAttribute('required');
                if (input.type === 'radio' || input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            }
        });
    }

    function updateConditionalQuestion() {
        if (triggerYes && triggerYes.checked) {
            setDependentVisibility(true);
            return;
        }

        if (triggerNo && triggerNo.checked) {
            setDependentVisibility(false);
            return;
        }

        setDependentVisibility(false);
    }

    if (triggerCard) {
        triggerCard.querySelectorAll('input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', updateConditionalQuestion);
        });
    }

    updateConditionalQuestion();

    const whyToggles = Array.from(document.querySelectorAll('.js-why-toggle'));

    function updateWhyBlocks() {
        const groups = {};

        whyToggles.forEach((radio) => {
            const targetId = radio.dataset.target;
            if (!groups[targetId]) {
                groups[targetId] = [];
            }
            groups[targetId].push(radio);
        });

        Object.keys(groups).forEach((targetId) => {
            const block = document.getElementById(targetId);
            if (!block) {
                return;
            }

            const radios = groups[targetId];
            const isSelected = radios.some((radio) => radio.checked);
            const textarea = block.querySelector('textarea');

            block.style.display = isSelected ? 'block' : 'none';

            if (textarea) {
                if (isSelected) {
                    textarea.setAttribute('required', 'required');
                } else {
                    textarea.removeAttribute('required');
                    textarea.value = '';
                }
            }
        });
    }

    whyToggles.forEach((radio) => {
        radio.addEventListener('change', updateWhyBlocks);
    });

    updateWhyBlocks();

    const ifYesToggles = Array.from(document.querySelectorAll('.js-if-yes-toggle'));

    function updateIfYesBlocks() {
        const groups = {};

        ifYesToggles.forEach((radio) => {
            const targetId = radio.dataset.target;
            if (!groups[targetId]) {
                groups[targetId] = [];
            }
            groups[targetId].push(radio);
        });

        Object.keys(groups).forEach((targetId) => {
            const block = document.getElementById(targetId);
            if (!block) {
                return;
            }

            const radios = groups[targetId];
            const selected = radios.find((radio) => radio.checked);
            const show = !!selected && selected.value === 'oui';
            const textarea = block.querySelector('textarea');

            block.style.display = show ? 'block' : 'none';

            if (textarea) {
                if (show) {
                    textarea.setAttribute('required', 'required');
                } else {
                    textarea.removeAttribute('required');
                    textarea.value = '';
                }
            }
        });
    }

    ifYesToggles.forEach((radio) => {
        radio.addEventListener('change', updateIfYesBlocks);
    });

    updateIfYesBlocks();
});
</script>
@endsection
