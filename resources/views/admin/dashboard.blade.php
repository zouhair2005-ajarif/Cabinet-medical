@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<h2 class="mb-4"><i class="bi bi-speedometer2"></i> Tableau de bord</h2>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5><i class="bi bi-people"></i> Patients</h5>
                <h2>{{ $stats['total_patients'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5><i class="bi bi-heart-pulse"></i> Médecins</h5>
                <h2>{{ $stats['total_medecins'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5><i class="bi bi-calendar"></i> Total RDV</h5>
                <h2>{{ $stats['total_rendezvous'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h5><i class="bi bi-calendar-check"></i> RDV Aujourd'hui</h5>
                <h2>{{ $stats['rdv_aujourd_hui'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header fw-bold">
                <i class="bi bi-bar-chart"></i> RDV par statut
            </div>
            <div class="card-body">
                <canvas id="rdvStatutChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header fw-bold">
                <i class="bi bi-pie-chart"></i> Répartition utilisateurs
            </div>
            <div class="card-body">
                <canvas id="usersChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('rdvStatutChart'), {
    type: 'bar',
    data: {
        labels: ['En attente', 'Accepté', 'Refusé', 'Terminé', 'Annulé'],
        datasets: [{
            label: 'Nombre de RDV',
            data: [
                {{ $stats['rdv_en_attente'] }},
                {{ $stats['rdv_accepte'] }},
                {{ $stats['rdv_refuse'] }},
                {{ $stats['rdv_termine'] }},
                {{ $stats['rdv_annule'] }}
            ],
            backgroundColor: [
                '#ffc107','#198754','#dc3545','#6c757d','#212529'
            ],
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

new Chart(document.getElementById('usersChart'), {
    type: 'doughnut',
    data: {
        labels: ['Patients', 'Médecins', 'Secrétaires'],
        datasets: [{
            data: [
                {{ $stats['total_patients'] }},
                {{ $stats['total_medecins'] }},
                {{ $stats['total_secretaires'] }}
            ],
            backgroundColor: ['#0d6efd','#198754','#ffc107'],
        }]
    },
    options: { responsive: true }
});
</script>
@endsection