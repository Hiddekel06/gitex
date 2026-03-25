@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 1100px; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Liste des équipes (vue groupée)</h2>
        <div class="mb-4 text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour au dashboard classique</a>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-dark table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Équipe</th>
                        <th>Membres</th>
                        <th>Réponses</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($equipes as $equipe)
                    <tr>
                        <td style="font-weight:600; color:#b6f7b6;">{{ $equipe->nom }}</td>
                        <td>
                            <ul class="mb-0" style="list-style:none; padding-left:0;">
                                @foreach($equipe->users as $user)
                                    <li>{{ $user->name }} <span class="text-muted" style="font-size:0.9em;">({{ $user->email }})</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @foreach($equipe->users as $user)
                                <div style="margin-bottom:0.7em;">
                                    <div style="font-weight:500; color:#e9f5e9;">{{ $user->name }}</div>
                                    <ul style="margin-bottom:0.3em;">
                                        @foreach($questions as $question)
                                            @php
                                                $rep = $reponses->where('user_id', $user->id)->where('question_id', $question->id)->first();
                                            @endphp
                                            <li>
                                                <span style="color:#ffe066;">{{ $question->intitule }}:</span>
                                                <span>{{ $rep ? $rep->reponse : '-' }}</span>
                                                @if($rep && $rep->justification)
                                                    <span class="text-info">(Justif: {{ $rep->justification }})</span>
                                                @endif
                                                @if($rep && $rep->int_value !== null)
                                                    <span class="text-warning">[Num: {{ $rep->int_value }}]</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Aucune équipe trouvée.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
