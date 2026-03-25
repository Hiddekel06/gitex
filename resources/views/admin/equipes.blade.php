@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 1100px; border-radius: 22px; background: rgba(34,34,34,0.97); border: 1px solid #2c2c2c;">
        <h2 class="mb-4 text-center" style="color:#e9f5e9; font-weight:600;">Liste des équipes (vue groupée)</h2>
        <div class="mb-4 text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour au dashboard classique</a>
            <a href="{{ route('admin.dashboard.equipes.export') }}" class="btn btn-success ms-2" style="font-weight:600;">
                Exporter en Excel
            </a>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-dark table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="color:#fff; background:transparent;">Équipe</th>
                        @foreach($questions as $question)
                            <th style="color:#fff; background:transparent; min-width:180px;">{{ $question->intitule }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @forelse($equipes as $equipe)
                    <tr>
                        <td style="font-weight:600; color:#b6f7b6; min-width:120px;">{{ $equipe->nom }}<br>
                            <ul class="mb-0" style="list-style:none; padding-left:0;">
                                @foreach($equipe->users as $user)
                                    <li style="font-size:0.95em; color:#fff;">{{ $user->name }} <span class="text-muted" style="font-size:0.9em;">({{ $user->email }})</span></li>
                                @endforeach
                            </ul>
                        </td>
                        @foreach($questions as $question)
                            <td style="vertical-align:top;">
                                @foreach($equipe->users as $user)
                                    @php
                                        $rep = $reponses->where('user_id', $user->id)->where('question_id', $question->id)->first();
                                    @endphp
                                    <div style="margin-bottom:0.5em;">
                                        <span>{{ $rep ? $rep->reponse : '-' }}</span>
                                        @if($rep && $rep->justification)
                                            <span class="text-info">(Justif: {{ $rep->justification }})</span>
                                        @endif
                                        @if($rep && $rep->int_value !== null)
                                            <span class="text-warning">[Num: {{ $rep->int_value }}]</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ 1 + $questions->count() }}" class="text-center">Aucune équipe trouvée.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
