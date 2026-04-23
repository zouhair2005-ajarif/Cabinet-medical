@extends('layouts.app')
@section('title', 'Nouvelle Consultation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-medical"></i> Consultation</h2>
    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<!-- Infos patient -->
<div class="card shadow-sm mb-3">
    <div class="card-header bg-info text-white fw-bold">
        <i class="bi bi-person"></i> Dossier Patient
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Patient :</strong>
                {{ $rendezvous->patient->user->name }}
            </div>
            <div class="col-md-4">
                <strong>Médecin :</strong>
                {{ $rendezvous->medecin->user->name }}
            </div>
            <div class="col-md-4">
                <strong>Date RDV :</strong>
                {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('d/m/Y H:i') }}
            </div>
        </div>
        @if($rendezvous->patient->dossierMedical)
        <hr>
        <div class="row">
            <div class="col-md-4">
                <strong>Antécédents :</strong><br>
                {{ $rendezvous->patient->dossierMedical->antecedents ?: 'Aucun' }}
            </div>
            <div class="col-md-4">
                <strong>Allergies :</strong><br>
                {{ $rendezvous->patient->dossierMedical->allergies ?: 'Aucune' }}
            </div>
            <div class="col-md-4">
                <strong>Maladies chroniques :</strong><br>
                {{ $rendezvous->patient->dossierMedical->maladies_chroniques ?: 'Aucune' }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Formulaire -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white fw-bold">
        <i class="bi bi-pencil"></i> Saisie de la Consultation
    </div>
    <div class="card-body">
        <form action="{{ route('consultations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="rendezvous_id" value="{{ $rendezvous->id }}">

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Compte rendu de consultation *
                </label>
                <textarea name="compte_rendu" class="form-control" rows="4"
                          placeholder="Décrivez le déroulement..." required>
                </textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Observations cliniques</label>
                <textarea name="observations" class="form-control" rows="3"
                          placeholder="Tension artérielle, température...">
                </textarea>
            </div>

            <hr>
            <h5 class="text-success">
                <i class="bi bi-capsule"></i> Ordonnance (optionnel)
            </h5>

            <div class="mb-3">
                <label class="form-label fw-bold">Médicaments prescrits</label>
                <textarea name="medicaments" class="form-control" rows="4"
                    placeholder="Ex: Paracétamol 1g - 3x/jour pendant 5 jours&#10;Amoxicilline 500mg - 2x/jour pendant 7 jours">
                </textarea>
                <small class="text-muted">
                    Entrez chaque médicament sur une ligne séparée.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Instructions pour le patient</label>
                <textarea name="instructions" class="form-control" rows="2"
                    placeholder="Repos conseillé, éviter...">
                </textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Enregistrer la consultation
                </button>
                <a href="{{ route('rendezvous.index') }}"
                   class="btn btn-outline-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection