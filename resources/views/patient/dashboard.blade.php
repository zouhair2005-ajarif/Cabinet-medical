@extends('layouts.app')
@section('title', 'Mon Espace')

@section('content')
<h2 class="mb-4">
    <i class="bi bi-person-circle"></i> Bonjour, {{ Auth::user()->name }}
</h2>

<!-- Notifications RDV -->
@php
    $rdvAcceptes = $rendezvous->where('statut','accepte')
        ->where('date_heure', '>', now());
    $rdvEnAttente = $rendezvous->where('statut','en_attente');
@endphp

@if($rdvAcceptes->count() > 0)
<div class="alert alert-success border-start border-success border-4 mb-4">
    <i class="bi bi-check-circle-fill"></i>
    <strong>{{ $rdvAcceptes->count() }} rendez-vous confirmé(s) :</strong>
    @foreach($rdvAcceptes as $rdv)
    <div class="ms-4 mt-1">
        📅 {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
        — Dr. {{ $rdv->medecin->user->name }}
    </div>
    @endforeach
</div>
@endif

@if($rdvEnAttente->count() > 0)
<div class="alert alert-warning border-start border-warning border-4 mb-4">
    <i class="bi bi-hourglass-split"></i>
    <strong>{{ $rdvEnAttente->count() }} rendez-vous en attente de confirmation</strong>
</div>
@endif

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h6><i class="bi bi-calendar-check"></i> Total RDV</h6>
                <h2>{{ $rendezvous->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h6><i class="bi bi-check2-all"></i> RDV Terminés</h6>
                <h2>{{ $rendezvous->where('statut','termine')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-dark shadow">
            <div class="card-body">
                <h6><i class="bi bi-hourglass"></i> En attente</h6>
                <h2>{{ $rdvEnAttente->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('rendezvous.create') }}"
           class="btn btn-primary btn-lg w-100">
            <i class="bi bi-calendar-plus"></i> Prendre un Rendez-vous
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('historique.index') }}"
           class="btn btn-outline-success btn-lg w-100">
            <i class="bi bi-file-medical"></i> Voir Mon Dossier Médical
        </a>
    </div>
</div>

<!-- Derniers RDV -->
<div class="card shadow-sm">
    <div class="card-header fw-bold bg-primary text-white">
        <i class="bi bi-clock-history"></i> Mes derniers rendez-vous
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Motif</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rendezvous->take(5) as $rdv)
                <tr>
                    <td>{{ $rdv->medecin->user->name }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                    </td>
                    <td>{{ $rdv->motif ?? '—' }}</td>
                    <td>
                        @php
                            $colors = [
                                'en_attente' => 'warning text-dark',
                                'accepte'    => 'success',
                                'refuse'     => 'danger',
                                'termine'    => 'secondary',
                                'annule'     => 'dark',
                            ];
                        @endphp
                        <span class="badge bg-{{ $colors[$rdv->statut] ?? 'secondary' }}">
                            {{ $rdv->statut }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        Aucun rendez-vous pour l'instant.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection