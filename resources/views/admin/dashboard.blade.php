@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 1100px; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Dashboard Admin</h2>
        <p class="text-center" style="color:#b0d2a0;">Liste des réponses par équipe</p>

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
                @foreach($equipes as $equipe)
                    @php
                        $usersEquipe = $users->where('equipe_id', $equipe->id);
                    @endphp
                    @foreach($usersEquipe as $user)
                        @php
                            $reponsesUser = $reponses->where('user_id', $user->id);
                        @endphp
                        @foreach($reponsesUser as $reponse)
                            @php
                                $question = $questions->where('id', $reponse->question_id)->first();
                            @endphp
                            <tr>
                                <td>{{ $equipe->nom }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $question ? $question->intitule : '' }}</td>
                                <td>{{ $reponse->reponse }}</td>
                                <td>{{ $reponse->justification }}</td>
                                <td>{{ $reponse->int_value }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
