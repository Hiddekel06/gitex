@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 700px; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Dashboard Admin</h2>
        <p class="text-center" style="color:#b0d2a0;">Bienvenue sur l’espace d’administration.<br>Vous pourrez bientôt consulter et exporter les réponses.</p>
        <div class="text-center mt-4">
            <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger">Se déconnecter</a>
        </div>
    </div>
</div>
@endsection
