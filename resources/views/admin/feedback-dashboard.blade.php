@extends('layouts.app')

@section('content')
<style>
    .feedback-admin-wrap {
        max-width: 1320px;
    }

    .glass-panel {
        border: 1px solid #2d2d2d;
        border-radius: 24px;
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95), rgba(28, 28, 28, 0.9));
        box-shadow: 0 30px 55px -35px rgba(0, 0, 0, 0.8);
    }

    .title-main {
        color: #edf9ed;
        font-weight: 700;
        letter-spacing: -0.4px;
    }

    .title-sub {
        color: #9db99a;
    }

    .kpi-card {
        border: 1px solid #303030;
        border-radius: 18px;
        padding: 1rem;
        background: rgba(34, 34, 34, 0.8);
        height: 100%;
    }

    .kpi-label {
        color: #a6bda2;
        font-size: 0.86rem;
        margin-bottom: 0.35rem;
    }

    .kpi-value {
        color: #f7fff7;
        font-weight: 700;
        font-size: 2rem;
        line-height: 1;
    }

    .chart-card {
        border: 1px solid #2f2f2f;
        border-radius: 18px;
        background: rgba(24, 24, 24, 0.82);
        padding: 1rem;
        height: 100%;
    }

    .chart-title {
        color: #d8ead3;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.8rem;
    }

    .table thead th {
        background: #454545;
        color: #fff;
    }

    .table td {
        color: #f2f2f2;
    }

    .empty-box {
        border: 1px dashed #575757;
        border-radius: 14px;
        color: #c3c3c3;
        background: rgba(40, 40, 40, 0.55);
    }
</style>

<div class="container-fluid feedback-admin-wrap py-4 py-md-5">
    <div class="glass-panel p-4 p-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="title-main mb-1">Dashboard Feedback GITEX</h2>
                <div class="title-sub">
                    @if($questionnaire)
                        {{ $questionnaire->titre }}
                    @else
                        Questionnaire introuvable
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">

                <a href="{{ route('admin.dashboard.feedback.responses') }}" class="btn btn-outline-info">Voir reponses detaillees</a>
            </div>
        </div>

        @if(!$questionnaire)
            <div class="empty-box p-4 text-center">Aucun questionnaire feedback actif n'a ete detecte.</div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-label">Soumissions</div>
                        <div class="kpi-value">{{ $kpi['totalSubmissions'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-label">Equipes representees</div>
                        <div class="kpi-value">{{ $kpi['uniqueTeams'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-label">Taux compl. moyen</div>
                        <div class="kpi-value">{{ $kpi['avgCompletionRate'] }}%</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-label">Note globale /5</div>
                        <div class="kpi-value">{{ $kpi['avgGlobalRating'] }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-xl-6">
                    <div class="chart-card">
                        <div class="chart-title">Repartition des soumissions par equipe</div>
                        <canvas id="teamsChart" height="240"></canvas>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="chart-card">
                        <div class="chart-title">Evolution des soumissions (par date)</div>
                        <canvas id="timelineChart" height="240"></canvas>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="chart-card">
                        <div class="chart-title">Contacts interessants</div>
                        <canvas id="contactsChart" height="220"></canvas>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="chart-card">
                        <div class="chart-title">Recommandation</div>
                        <canvas id="recommendationChart" height="220"></canvas>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="kpi-card h-100">
                        <div class="kpi-label mb-1">Note organisation /5</div>
                        <div class="kpi-value">{{ $kpi['avgOrganizationRating'] }}</div>
                        <div class="mt-3" style="color:#a9c0a6; font-size:0.92rem;">
                            Ces KPI sont calcules sur les soumissions enregistrees
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">Dernieres soumissions</div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Email</th>
                                <th>Equipe</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestSubmissions as $item)
                                <tr>
                                    <td>{{ $item['member'] }}</td>
                                    <td>{{ $item['email'] }}</td>
                                    <td>{{ $item['team'] }}</td>
                                    <td>{{ $item['submitted_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune soumission pour le moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@if($questionnaire)
<script id="teams-data" type="application/json">@json($charts['teams'])</script>
<script id="submissions-data" type="application/json">@json($charts['submissionsByDate'])</script>
<script id="contacts-data" type="application/json">@json($charts['contacts'])</script>
<script id="recommendation-data" type="application/json">@json($charts['recommendation'])</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const teamsData = JSON.parse(document.getElementById('teams-data').textContent);
    const submissionsByDate = JSON.parse(document.getElementById('submissions-data').textContent);
    const contactsData = JSON.parse(document.getElementById('contacts-data').textContent);
    const recommendationData = JSON.parse(document.getElementById('recommendation-data').textContent);

    const gridColor = 'rgba(255, 255, 255, 0.10)';
    const labelColor = '#d3dfcf';

    new Chart(document.getElementById('teamsChart'), {
        type: 'bar',
        data: {
            labels: teamsData.labels,
            datasets: [{
                label: 'Soumissions',
                data: teamsData.values,
                backgroundColor: ['#62955b', '#4e7dd1', '#d68543', '#9d5ad8', '#b84f66', '#5e9f9f'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { ticks: { color: labelColor }, grid: { color: gridColor } },
                y: { ticks: { color: labelColor }, grid: { color: gridColor }, beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('timelineChart'), {
        type: 'line',
        data: {
            labels: submissionsByDate.labels,
            datasets: [{
                label: 'Soumissions',
                data: submissionsByDate.values,
                borderColor: '#76b86e',
                backgroundColor: 'rgba(118, 184, 110, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: labelColor }, grid: { color: gridColor } },
                y: { ticks: { color: labelColor }, grid: { color: gridColor }, beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('contactsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Oui', 'Non'],
            datasets: [{
                data: [contactsData.oui, contactsData.non],
                backgroundColor: ['#6ab96a', '#d86a6a'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: labelColor } }
            }
        }
    });

    new Chart(document.getElementById('recommendationChart'), {
        type: 'doughnut',
        data: {
            labels: ['Oui', 'Non'],
            datasets: [{
                data: [recommendationData.oui, recommendationData.non],
                backgroundColor: ['#4cb187', '#cc6b55'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: labelColor } }
            }
        }
    });
</script>
@endif
@endsection
