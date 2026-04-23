@extends('layouts.app')
@section('title', 'Dossier Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person"></i> {{ $patient->user->name }}</h2>
    <div class="d-flex gap-2">
        @if(Auth::user()->role === 'medecin')
        <a href="{{ route('historique.show', $patient->id) }}"
           class="btn btn-success">
            <i class="bi bi-folder2-open"></i> Dossier Médical
        </a>
        @endif
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-person-circle"></i> Informations personnelles
            </div>
            <div class="card-body">
                <p><strong>Nom :</strong> {{ $patient->user->name }}</p>
                <p><strong>Email :</strong> {{ $patient->user->email }}</p>
                <p><strong>Téléphone :</strong> {{ $patient->telephone ?? '—' }}</p>
                <p>
                    <strong>Date de naissance :</strong>
                    {{ $patient->date_naissance ?? '—' }}
                    @if($patient->date_naissance)
                        ({{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans)
                    @endif
                </p>
                <p><strong>Adresse :</strong> {{ $patient->adresse ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-file-medical"></i> Résumé Dossier Médical
            </div>
            <div class="card-body">
                @if($patient->dossierMedical)
                    <p>
                        <strong>Antécédents :</strong>
                        {{ $patient->dossierMedical->antecedents ?: 'Aucun' }}
                    </p>
                    <p>
                        <strong>Allergies :</strong>
                        {{ $patient->dossierMedical->allergies ?: 'Aucune' }}
                    </p>
                    <p>
                        <strong>Maladies chroniques :</strong>
                        {{ $patient->dossierMedical->maladies_chroniques ?: 'Aucune' }}
                    </p>
                    <p>
                        <strong>Diagnostics :</strong>
                        {{ $patient->dossierMedical->diagnostics ?: 'Aucun' }}
                    </p>
                @else
                    <p class="text-muted">Aucun dossier médical.</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-info text-white fw-bold">
                <i class="bi bi-calendar-check"></i> Derniers Rendez-vous
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Médecin</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->rendezvous->take(5) as $rdv)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y') }}
                            </td>
                            <td>{{ $rdv->medecin->user->name }}</td>
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
                            <td colspan="3" class="text-center text-muted">
                                Aucun rendez-vous.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection