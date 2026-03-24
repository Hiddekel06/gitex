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
        border-left: 4px solid #e53935; /* rouge vif */
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

    /* Input wrapper for better focus effect */
    .input-group-custom {
        position: relative;
        margin-bottom: 1.5rem;
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

    /* Floating label effect (optional) */
    .input-group-custom .form-control:focus + .form-label,
    .input-group-custom .form-control:not(:placeholder-shown) + .form-label {
        color: #5a8f4c;
    }

    /* Button with ripple and gradient */
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
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(90,143,76,0.18);
    }
    .btn-modern::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }
    .btn-modern:active::after {
        width: 200px;
        height: 200px;
    }
    .btn-modern:hover {
        background: linear-gradient(95deg, #3c6e2f, #2e5822);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(90, 143, 76, 0.22);
    }
    .btn-modern:active {
        transform: translateY(1px);
    }

    /* Custom alert */
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
    .alert-custom ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    .alert-custom li {
        margin-bottom: 0.25rem;
    }

    /* Responsive tweaks */
    @media (max-width: 480px) {
        .modern-card {
            margin-left: 1rem;
            margin-right: 1rem;
            padding: 1.5rem !important;
        }
        .form-control, .btn-modern {
            font-size: 0.9rem;
            padding: 0.7rem 1rem;
        }
        .form-label {
            font-size: 0.8rem;
        }
        .form-title {
            font-size: 1.35rem;
        }
    }

    /* Smooth fade-in animation */
    .modern-card {
        animation: fadeSlideUp 0.5s ease-out;
    }
    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hero message */
    .hero-message {
        text-align: center;
        margin-bottom: 1.5rem;
        animation: fadeSlideUp 0.6s ease;
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

<!-- Optional grain overlay -->
<div class="grain"></div>

<div class="d-flex justify-content-center align-items-center min-vh-100 py-4 py-md-5">
    <div class="modern-card p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="hero-message">
            <div class="hero-title">
                 En route pour le GITEX 2026 !
            </div>
            <div class="hero-subtitle">
                Une étape de plus vers l’innovation mondiale
            </div>
        </div>
        <h2 class="form-title">Finaliste Gov'Athon</h2>

        @if ($errors->any())
            <div class="alert-custom">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('identification.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required placeholder="">
            </div>

            <div class="mb-4">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required placeholder="">
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="">
            </div>

            <div class="mb-4">
                <label for="equipe_id" class="form-label">Équipe</label>
                <select class="form-select" id="equipe_id" name="equipe_id" required>
                    <option value="">Sélectionner une équipe</option>
                    @foreach($equipes as $equipe)
                        <option value="{{ $equipe->id }}" {{ old('equipe_id') == $equipe->id ? 'selected' : '' }}>{{ $equipe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-modern mt-2">Valider</button>
        </form>
    </div>
</div>
@endsection