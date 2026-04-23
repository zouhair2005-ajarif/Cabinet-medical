@extends('layouts.app')
@section('title', 'Dossier Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-folder2-open"></i>
        Dossier de {{ $patient->user->name }}
    </h2>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Modifier dossier -->
@if(Auth::user()->role === 'medecin')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning fw-bold">
        <i class="bi bi-pencil"></i> Modifier le dossier médical
    </div>
    <div class="card-body">
        <form action="{{ route('historique.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Antécédents</label>
                    <textarea name="antecedents" class="form-control" rows="2">
{{ $patient->dossierMedical->antecedents ?? '' }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Allergies</label>
                    <textarea name="allergies" class="form-control" rows="2">
{{ $patient->dossierMedical->allergies ?? '' }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Maladies chroniques</label>
                    <textarea name="maladies_chroniques" class="form-control" rows="2">
{{ $patient->dossierMedical->maladies_chroniques ?? '' }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Diagnostics</label>
                    <textarea name="diagnostics" class="form-control" rows="2">
{{ $patient->dossierMedical->diagnostics ?? '' }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </form>
    </div>
</div>
@endif

<!-- Consultations -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">
        <i class="bi bi-clock-history"></i> Historique des Consultations
    </div>
    <div class="card-body p-0">
        @forelse($patient->dossierMedical->consultations->sortByDesc('date') as $c)
        <div class="border-bottom p-3">
            <div class="d-flex justify-content-between">
                <strong>
                    {{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}
                    — {{ $c->rendezvous->medecin->user->name }}
                </strong>
                @if($c->ordonnance)
                <a href="{{ route('consultations.pdf', $c->ordonnance->id) }}"
                   class="btn btn-sm btn-danger" target="_blank">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
                @endif
            </div>
            <p class="mb-1 mt-2">
                <strong>Compte rendu :</strong> {{ $c->compte_rendu }}
            </p>
            @if($c->observations)
            <p class="mb-1 text-muted">
                <strong>Observations :</strong> {{ $c->observations }}
            </p>
            @endif
            @if($c->ordonnance)
            <div class="bg-light p-2 rounded mt-2">
                <strong><i class="bi bi-capsule"></i> Médicaments :</strong>
                <pre class="mb-0 small">{{ $c->ordonnance->medicaments }}</pre>
            </div>
            @endif
        </div>
        @empty
        <div class="p-4 text-center text-muted">Aucune consultation.</div>
        @endforelse
    </div>
</div>
@endsection