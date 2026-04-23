@extends('layouts.app')
@section('title', 'Détail RDV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check"></i> Rendez-vous #{{ $rendezvous->id }}</h2>
    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    {{-- Infos RDV --}}
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <p><strong>Patient :</strong> {{ $rendezvous->patient->user->name }}</p>
                <p><strong>Médecin :</strong> {{ $rendezvous->medecin->user->name }}</p>
                <p><strong>Spécialité :</strong> {{ $rendezvous->medecin->specialite }}</p>
                <p><strong>Date :</strong>
                    {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('d/m/Y à H:i') }}
                </p>
                <p><strong>Motif :</strong> {{ $rendezvous->motif ?? '—' }}</p>
                <p><strong>Statut :</strong>
                    @php
                        $colors = [
                            'en_attente' => 'warning',
                            'accepte'    => 'success',
                            'refuse'     => 'danger',
                            'termine'    => 'secondary',
                            'annule'     => 'dark',
                        ];
                        $c = $colors[$rendezvous->statut] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $c }}">{{ $rendezvous->statut }}</span>
                </p>
                @if($rendezvous->commentaire)
                <div class="alert alert-warning mt-2">
                    <strong>💬 Commentaire :</strong>
                    {{ $rendezvous->commentaire }}
                </div>
                @endif
            </div>
        </div>

        {{-- Actions médecin --}}
        @if(Auth::user()->role === 'medecin')
            @if($rendezvous->statut === 'en_attente')
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Actions :</h6>
                    <div class="d-flex gap-2">
                        <form action="{{ route('rendezvous.accepter', $rendezvous) }}"
                              method="POST">
                            @csrf
                            <button class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Accepter
                            </button>
                        </form>
                        <button class="btn btn-danger"
                                onclick="ouvrirRefus({{ $rendezvous->id }})">
                            <i class="bi bi-x-circle"></i> Refuser
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if($rendezvous->statut === 'accepte' && !$rendezvous->consultation)
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <a href="{{ route('consultations.create', $rendezvous) }}"
                       class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle"></i> Démarrer la consultation
                    </a>
                </div>
            </div>
            @endif
        @endif
    </div>

    {{-- Consultation + Dossier --}}
    <div class="col-md-6">
        @if($rendezvous->consultation)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-file-medical"></i> Consultation
            </div>
            <div class="card-body">
                <p><strong>Compte rendu :</strong><br>
                    {{ $rendezvous->consultation->compte_rendu }}
                </p>
                @if($rendezvous->consultation->observations)
                <p><strong>Observations :</strong><br>
                    {{ $rendezvous->consultation->observations }}
                </p>
                @endif
                @if($rendezvous->consultation->ordonnance)
                <div class="mt-3">
                    <strong><i class="bi bi-capsule"></i> Ordonnance :</strong>
                    <pre class="bg-light p-2 rounded mt-1 small">
{{ $rendezvous->consultation->ordonnance->medicaments }}</pre>
                    <a href="{{ route('consultations.pdf',
                               $rendezvous->consultation->ordonnance->id) }}"
                       class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> Télécharger PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Dossier médical pour médecin --}}
        @if(Auth::user()->role === 'medecin' && $rendezvous->patient->dossierMedical)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning fw-bold">
                <i class="bi bi-folder2-open"></i> Dossier Médical du Patient
            </div>
            <div class="card-body">
                <p><strong>Antécédents :</strong>
                    {{ $rendezvous->patient->dossierMedical->antecedents ?: 'Aucun' }}
                </p>
                <p><strong>Allergies :</strong>
                    {{ $rendezvous->patient->dossierMedical->allergies ?: 'Aucune' }}
                </p>
                <p><strong>Maladies chroniques :</strong>
                    {{ $rendezvous->patient->dossierMedical->maladies_chroniques ?: 'Aucune' }}
                </p>
                <a href="{{ route('historique.show', $rendezvous->patient_id) }}"
                   class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-clock-history"></i>
                    Voir historique complet
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal refus --}}
<div class="modal fade" id="modalRefus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">❌ Refuser le RDV</h5>
                <button class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <form id="formRefus" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-bold">Motif du refus :</label>
                    <textarea name="commentaire" class="form-control" rows="3"
                              placeholder="Raison du refus..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function ouvrirRefus(rdvId) {
    document.getElementById('formRefus').action = `/rendezvous/${rdvId}/refuser`;
    new bootstrap.Modal(document.getElementById('modalRefus')).show();
}
</script>
@endsection