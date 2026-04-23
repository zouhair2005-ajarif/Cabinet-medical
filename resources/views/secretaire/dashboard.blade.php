@extends('layouts.app')
@section('title', 'Dashboard Secrétaire')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard"></i> Tableau de bord Secrétaire</h2>

<div class="alert alert-info">
    <i class="bi bi-people"></i> Total patients : <strong>{{ $total_patients }}</strong>
</div>

<div class="card shadow-sm">
    <div class="card-header fw-bold">
        <i class="bi bi-calendar-check"></i> Derniers rendez-vous
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-warning">
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rendezvous as $rdv)
                <tr>
                    <td>{{ $rdv->patient->user->name }}</td>
                    <td>{{ $rdv->medecin->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-info">{{ $rdv->statut }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Aucun rendez-vous.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection