@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Connexion Admin</h2>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 mt-2">Se connecter</button>
        </form>
    </div>
</div>
@endsection
