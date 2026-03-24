@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 1100px; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Dashboard Admin</h2>
        <p class="text-center" style="color:#b0d2a0;">Sélectionnez une équipe pour afficher ses réponses</p>

        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4 d-flex justify-content-center align-items-center gap-3">
            <select name="equipe_id" class="form-select" style="max-width: 320px;">
                <option value="">-- Choisir une équipe --</option>
                @foreach($equipes as $equipe)
                    <option value="{{ $equipe->id }}" {{ (isset($selectedEquipeId) && $selectedEquipeId == $equipe->id) ? 'selected' : '' }}>{{ $equipe->nom }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success">Afficher</button>
        </form>

        @if($selectedEquipeId && $users->count())
        <div class="table-responsive mt-4">
            <table class="table table-dark table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Équipe</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Question</th>
                        <th>Réponse</th>
                        <th>Justification</th>
                        <th>Valeur numérique</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @php
                        $reponsesUser = $reponses->where('user_id', $user->id);
                    @endphp
                    @foreach($reponsesUser as $reponse)
                        @php
                            $question = $questions->where('id', $reponse->question_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $user->equipe ? $user->equipe->nom : '' }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $question ? $question->intitule : '' }}</td>
                            <td>{{ $reponse->reponse }}</td>
                            <td>{{ $reponse->justification }}</td>
                            <td>{{ $reponse->int_value }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
        @elseif($selectedEquipeId)
            <div class="alert alert-warning text-center mt-4">Aucune réponse trouvée pour cette équipe.</div>
        @endif
    </div>
</div>
@endsection
