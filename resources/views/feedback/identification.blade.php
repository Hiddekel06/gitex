@extends('layouts.app')

@section('content')

<style>

    /* Modern card */
    .modern-card {
        position: relative;
        z-index: 2;
        border: none;
        border-radius: 32px;
        background: rgba(24, 24, 24, 0.8);
        backdrop-filter: blur(12px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.4), 0 1px 1px rgba(255, 255, 255, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.5);
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        background: linear-gradient(135deg, #ffffff, #b0d2a0);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        margin-bottom: 1.5rem;
        letter-spacing: -0.3px;
        border-left: 4px solid #e53935;
        padding-left: 1rem;
        display: inline-block;
    }

    @media (min-width: 768px) {
        .form-title {
            font-size: 1.75rem;
            margin-bottom: 1.75rem;
        }
        .modern-card {
            padding: 2rem !important;
        }
    }

    .form-label {
        font-weight: 500;
        color: #cfdec9;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        letter-spacing: -0.2px;
        transition: color 0.2s;
    }

    .form-control, .form-select {
        border: 1px solid #2c2c2c;
        border-radius: 16px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        line-height: 1.5;
        background-color: #1a1a1a;
        color: #f0f0f0;
        transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }

    .form-control:hover, .form-select:hover {
        border-color: #5a8f4c;
        background-color: #1f1f1f;
    }

    .form-control:focus, .form-select:focus {
        border-color: #5a8f4c;
        box-shadow: 0 0 0 4px rgba(90, 143, 76, 0.2);
        outline: none;
        background-color: #1f1f1f;
        color: #fff;
        transform: scale(1.01);
    }

    .btn-modern {
        background: linear-gradient(95deg, #5a8f4c 60%, #3c6e2f 100%);
        border: none;
        border-radius: 40px;
        padding: 0.85rem 1.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        color: white;
        transition: all 0.25s ease;
        width: 100%;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 12px rgba(90,143,76,0.18);
    }

    .btn-modern:hover {
        background: linear-gradient(95deg, #3c6e2f, #2e5822);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(90, 143, 76, 0.22);
    }

    .alert-custom {
        background: rgba(44, 44, 44, 0.93);
        backdrop-filter: blur(4px);
        border-left: 4px solid #e53935;
        border-radius: 20px;
        color: #ffb366;
        padding: 0.75rem 1rem;
        margin-bottom: 1.75rem;
        font-size: 0.875rem;
        box-shadow: 0 0 8px 0 #e5393533;
    }

    .hero-message {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #5a8f4c, #9ee29e, #ffffff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-subtitle {
        font-size: 0.9rem;
        color: #9aa89a;
        margin-top: 0.4rem;
        letter-spacing: 0.2px;
    }
</style>

<div class="grain"></div>

<div class="d-flex justify-content-center align-items-center min-vh-100 py-4 py-md-5">
    <div class="modern-card p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="hero-message">
            <div class="hero-title">
                 Feedback GITEX 2026
            </div>
            <div class="hero-subtitle">
                Identification de tous les membres de l'equipe
            </div>
        </div>
        <h2 class="form-title">Nouveau formulaire</h2>

        @if ($errors->any())
            <div class="alert-custom">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('feedback.identification.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="prenom" class="form-label">Prenom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
            </div>

            <div class="mb-4">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="equipe_id" class="form-label">Equipe</label>
                <select class="form-select" id="equipe_id" name="equipe_id" required>
                    <option value="">Selectionner votre equipe</option>
                    @foreach($equipes as $equipe)
                        <option value="{{ $equipe->id }}" {{ old('equipe_id') == $equipe->id ? 'selected' : '' }}>{{ $equipe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-modern mt-2">Continuer</button>
        </form>
    </div>
</div>
@endsection
