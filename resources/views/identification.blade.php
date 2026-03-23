@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap');
    body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif; }
    .modern-card {
        border: none;
        border-radius: 20px;
        background-color: #181818;
        color: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.18);
        transition: all 0.2s ease;
    }
    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 1.5rem;
        letter-spacing: -0.3px;
        border-left: 4px solid #5a8f4c;
        padding-left: 1rem;
    }
    @media (min-width: 768px) {
        .form-title { font-size: 1.75rem; margin-bottom: 1.75rem; }
        .modern-card { padding: 2rem !important; }
    }
    .form-label {
        font-weight: 500;
        color: #e9f5e9;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        letter-spacing: -0.2px;
    }
    .form-control, .form-select {
        border: 1px solid #333;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        line-height: 1.5;
        background-color: #222;
        color: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #5a8f4c;
        box-shadow: 0 0 0 3px rgba(90, 143, 76, 0.15);
        outline: none;
        background-color: #222;
        color: #fff;
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
    .btn-modern:hover { background-color: #457a37; transform: translateY(-1px); }
    .btn-modern:active { transform: translateY(1px); }
    .alert-custom {
        background-color: #2c2c2c;
        border-left: 4px solid #d68c3c;
        border-radius: 12px;
        color: #ffb366;
        padding: 0.75rem 1rem;
        margin-bottom: 1.75rem;
        font-size: 0.875rem;
    }
    .alert-custom ul { margin: 0; padding-left: 1.25rem; }
    .alert-custom li { margin-bottom: 0.25rem; }
    @media (max-width: 480px) {
        .modern-card { margin-left: 1rem; margin-right: 1rem; }
        .form-control, .btn-modern { font-size: 0.9rem; padding: 0.7rem 1rem; }
        .form-label { font-size: 0.8rem; }
        .form-title { font-size: 1.35rem; }
    }
</style>

<div class="d-flex justify-content-center align-items-center min-vh-100 py-4 py-md-5">
    <div class="modern-card p-4 p-md-5" style="max-width: 500px; width: 100%;">
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
                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required placeholder="Votre prénom">
            </div>
            <div class="mb-4">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required placeholder="Votre nom">
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="Votre email">
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
