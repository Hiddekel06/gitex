@extends('layouts.app')

@section('content')
<style>
    .feedback-responses-wrap {
        max-width: 1380px;
    }

    .panel {
        border: 1px solid #2d2d2d;
        border-radius: 24px;
        background: linear-gradient(145deg, rgba(21, 21, 21, 0.95), rgba(30, 30, 30, 0.9));
        box-shadow: 0 25px 60px -35px rgba(0, 0, 0, 0.85);
    }

    .title {
        color: #ecf8ec;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .subtitle {
        color: #9fb79b;
    }

    .filter-box {
        border: 1px solid #343434;
        border-radius: 16px;
        background: rgba(31, 31, 31, 0.8);
    }

    .form-select {
        background: #1f1f1f;
        color: #fff;
        border: 1px solid #404040;
    }

    .table thead th {
        background: #4a4a4a;
        color: #fff;
        white-space: nowrap;
    }

    .table td {
        color: #f2f2f2;
        vertical-align: middle;
    }

    .pill {
        display: inline-block;
        border-radius: 99px;
        padding: 0.2rem 0.6rem;
        font-size: 0.78rem;
        border: 1px solid #4d4d4d;
        color: #d3e8d3;
        background: rgba(70, 70, 70, 0.4);
    }

    .empty {
        border: 1px dashed #5f5f5f;
        border-radius: 14px;
        color: #c4c4c4;
        background: rgba(42, 42, 42, 0.55);
    }
</style>

<div class="container-fluid feedback-responses-wrap py-4 py-md-5">
    <div class="panel p-4 p-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="title mb-1">Reponses detaillees</h2>
                <div class="subtitle">
                    @if($questionnaire)
                        {{ $questionnaire->titre }}
                    @else
                        Questionnaire feedback introuvable
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dashboard.feedback') }}" class="btn btn-outline-info">Retour dashboard feedback</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">Dashboard classique</a>
            </div>
        </div>

        @if(!$questionnaire)
            <div class="empty p-4 text-center">Aucun questionnaire feedback actif n'a ete detecte.</div>
        @else
            <div class="filter-box p-3 mb-4">
                <form method="GET" action="{{ route('admin.dashboard.feedback.responses') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="equipe_id" class="form-label" style="color:#d0e5cc;">Filtrer par equipe</label>
                        <select class="form-select" id="equipe_id" name="equipe_id">
                            <option value="">Toutes les equipes</option>
                            @foreach($equipes as $equipe)
                                <option value="{{ $equipe->id }}" {{ $selectedEquipeId == $equipe->id ? 'selected' : '' }}>{{ $equipe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-success">Appliquer</button>
                        <a href="{{ route('admin.dashboard.feedback.responses') }}" class="btn btn-outline-secondary ms-2">Reinitialiser</a>
                    </div>
                </form>
            </div>

            <div class="mb-3" style="color:#a8c3a3;">
                <span class="pill">{{ $rows->count() }} lignes affichees</span>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Equipe</th>
                            <th>Membre</th>
                            <th>Email</th>
                            <th>Section</th>
                            <th>Question</th>
                            <th>Reponse</th>
                            <th>Justification</th>
                            <th>Valeur num.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['equipe'] }}</td>
                                <td>{{ $row['member'] }}</td>
                                <td>{{ $row['email'] }}</td>
                                <td>{{ $row['section'] }}</td>
                                <td>{{ $row['question'] }}</td>
                                <td>{{ $row['reponse'] }}</td>
                                <td>{{ $row['justification'] }}</td>
                                <td>{{ $row['int_value'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucune reponse detaillee pour ce filtre.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
