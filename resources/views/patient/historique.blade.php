@extends('layouts.app')
@section('title', 'Mon Historique Médical')

@section('content')
<h2 class="mb-4">
    <i class="bi bi-file-medical"></i> Mon Dossier Médical
</h2>

<!-- Infos dossier -->
@if($patient->dossierMedical)
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-heart-pulse"></i> Antécédents
            </div>
            <div class="card-body">
                {{ $patient->dossierMedical->antecedents ?: 'Aucun antécédent enregistré.' }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning shadow-sm h-100">
            <div class="card-header bg-warning fw-bold">
                <i class="bi bi-exclamation-triangle"></i> Allergies
            </div>
            <div class="card-body">
                {{ $patient->dossierMedical->allergies ?: 'Aucune allergie connue.' }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info shadow-sm h-100">
            <div class="card-header bg-info text-white fw-bold">
                <i class="bi bi-activity"></i> Maladies chroniques
            </div>
            <div class="card-body">
                {{ $patient->dossierMedical->maladies_chroniques ?: 'Aucune maladie chronique.' }}
            </div>
        </div>
    </div>
</div>

<!-- Historique consultations -->
<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold bg-success text-white">
        <i class="bi bi-clock-history"></i> Historique des Consultations
    </div>
    <div class="card-body p-0">
        @if($patient->dossierMedical->consultations->isEmpty())
            <div class="p-4 text-center text-muted">
                Aucune consultation enregistrée.
            </div>
        @else
            @foreach($patient->dossierMedical->consultations->sortByDesc('date') as $c)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>
                            <i class="bi bi-calendar"></i>
                            {{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}
                        </strong>
                        — Dr. {{ $c->rendezvous->medecin->user->name }}
                        ({{ $c->rendezvous->medecin->specialite }})
                    </div>
                    @if($c->ordonnance)
                    <a href="{{ route('consultations.pdf', $c->ordonnance->id) }}"
                       class="btn btn-sm btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> Ordonnance PDF
                    </a>
                    @endif
                </div>
                <div class="mt-2">
                    <strong>Compte rendu :</strong>
                    {{ $c->compte_rendu }}
                </div>
                @if($c->observations)
                <div class="mt-1 text-muted">
                    <strong>Observations :</strong> {{ $c->observations }}
                </div>
                @endif
                @if($c->ordonnance)
                <div class="mt-2 p-2 bg-light rounded">
                    <strong><i class="bi bi-capsule"></i> Ordonnance :</strong><br>
                    <pre class="mb-0 small">{{ $c->ordonnance->medicaments }}</pre>
                </div>
                @endif
            </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Historique RDV -->
<div class="card shadow-sm">
    <div class="card-header fw-bold bg-primary text-white">
        <i class="bi bi-calendar-check"></i> Tous mes Rendez-vous
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Médecin</th>
                    <th>Motif</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patient->rendezvous->sortByDesc('date_heure') as $rdv)
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                    </td>
                    <td>{{ $rdv->medecin->user->name }}</td>
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
                    <td colspan="4" class="text-center text-muted">
                        Aucun rendez-vous.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="alert alert-warning">
    Dossier médical non trouvé.
</div>
@endif
@endsection