@extends('layouts.app')
@section('title', 'Rendez-vous')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check"></i> Rendez-vous</h2>
    @if(Auth::user()->role !== 'medecin')
    <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau RDV
    </a>
    @endif
</div>

{{-- Onglets médecin --}}
@if(Auth::user()->role === 'medecin')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request('filtre') || request('filtre') == 'en_attente' ? 'active' : '' }}"
           href="{{ route('rendezvous.index') }}?filtre=en_attente">
            ⏳ En attente
            <span class="badge bg-warning text-dark">
                {{ $rendezvous->where('statut','en_attente')->count() }}
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('filtre') == 'accepte' ? 'active' : '' }}"
           href="{{ route('rendezvous.index') }}?filtre=accepte">
            ✅ Acceptés
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('filtre') == 'tous' ? 'active' : '' }}"
           href="{{ route('rendezvous.index') }}?filtre=tous">
            📋 Tous
        </a>
    </li>
</ul>
@endif

{{-- Modal Refus avec commentaire --}}
<div class="modal fade" id="modalRefus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">❌ Refuser le rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <form id="formRefus" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-bold">
                        Motif du refus (optionnel) :
                    </label>
                    <textarea name="commentaire" class="form-control" rows="3"
                              placeholder="Ex: Pas disponible ce jour..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        ❌ Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Annulation avec commentaire --}}
<div class="modal fade" id="modalAnnulation" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">🚫 Annuler le rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <form id="formAnnulation" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">
                        Un email sera envoyé au patient pour l'informer.
                    </p>
                    <label class="form-label fw-bold">
                        Raison de l'annulation (optionnel) :
                    </label>
                    <textarea name="commentaire" class="form-control" rows="3"
                              placeholder="Ex: Empêchement personnel..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-dark">
                        🚫 Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    @if(Auth::user()->role !== 'patient')
                        <th>Patient</th>
                    @endif
                    @if(Auth::user()->role !== 'medecin')
                        <th>Médecin</th>
                    @endif
                    <th>Date & Heure</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rendezvous as $rdv)
                <tr>
                    <td>{{ $rdv->id }}</td>
                    @if(Auth::user()->role !== 'patient')
                        <td>{{ $rdv->patient->user->name }}</td>
                    @endif
                    @if(Auth::user()->role !== 'medecin')
                        <td>{{ $rdv->medecin->user->name }}</td>
                    @endif
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
                            $c = $colors[$rdv->statut] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $c }}">{{ $rdv->statut }}</span>
                        @if($rdv->commentaire)
                            <br><small class="text-muted">
                                💬 {{ Str::limit($rdv->commentaire, 30) }}
                            </small>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            {{-- Voir --}}
                            <a href="{{ route('rendezvous.show', $rdv) }}"
                               class="btn btn-sm btn-info" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Médecin : Accepter / Refuser --}}
                            @if(Auth::user()->role === 'medecin')
                                @if($rdv->statut === 'en_attente')
                                    <form action="{{ route('rendezvous.accepter', $rdv) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                title="Accepter">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-danger"
                                            title="Refuser"
                                            onclick="ouvrirModalRefus({{ $rdv->id }})">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                @endif

                                {{-- Bouton consultation si accepté --}}
                                @if($rdv->statut === 'accepte' && !$rdv->consultation)
                                    <a href="{{ route('consultations.create', $rdv) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Démarrer consultation">
                                        <i class="bi bi-file-medical"></i>
                                    </a>
                                @endif

                                {{-- Voir ordonnance si terminé --}}
                                @if($rdv->statut === 'termine' && $rdv->consultation?->ordonnance)
                                    <a href="{{ route('consultations.pdf',
                                               $rdv->consultation->ordonnance->id) }}"
                                       class="btn btn-sm btn-danger"
                                       title="Ordonnance PDF" target="_blank">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                @endif

                                {{-- Voir dossier patient --}}
                                <a href="{{ route('historique.show', $rdv->patient_id) }}"
                                   class="btn btn-sm btn-secondary"
                                   title="Dossier patient">
                                    <i class="bi bi-folder2"></i>
                                </a>
                            @endif

                            {{-- Patient : Annuler son RDV --}}
                            @if(Auth::user()->role === 'patient')
                                @if(in_array($rdv->statut, ['en_attente', 'accepte']))
                                    <button class="btn btn-sm btn-dark"
                                            title="Annuler mon RDV"
                                            onclick="ouvrirModalAnnulation({{ $rdv->id }})">
                                        <i class="bi bi-x-circle"></i> Annuler
                                    </button>
                                @endif
                            @endif

                            {{-- Admin/Secrétaire --}}
                            @if(in_array(Auth::user()->role, ['admin','secretaire']))
                                <a href="{{ route('rendezvous.edit', $rdv) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!in_array($rdv->statut, ['annule','refuse','termine']))
                                    <button class="btn btn-sm btn-dark"
                                            onclick="ouvrirModalAnnulation({{ $rdv->id }})">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Aucun rendez-vous trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function ouvrirModalRefus(rdvId) {
    const form = document.getElementById('formRefus');
    form.action = `/rendezvous/${rdvId}/refuser`;
    new bootstrap.Modal(document.getElementById('modalRefus')).show();
}

function ouvrirModalAnnulation(rdvId) {
    const form = document.getElementById('formAnnulation');
    form.action = `/rendezvous/${rdvId}/annuler`;
    new bootstrap.Modal(document.getElementById('modalAnnulation')).show();
}
</script>
@endsection