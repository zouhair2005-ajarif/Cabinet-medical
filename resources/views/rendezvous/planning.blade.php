@extends('layouts.app')
@section('title', 'Planning')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-week"></i> Planning Médical</h2>
</div>

{{-- Sélecteur médecin pour Admin/Secrétaire --}}
@if($medecins)
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('rendezvous.planning') }}"
              class="d-flex align-items-center gap-3">
            <label class="fw-bold mb-0">Choisir un médecin :</label>
            <select name="medecin_id" class="form-select w-auto"
                    onchange="this.form.submit()">
                @foreach($medecins as $m)
                    <option value="{{ $m->id }}"
                        {{ $medecin?->id == $m->id ? 'selected' : '' }}>
                        {{ $m->user->name }} — {{ $m->specialite }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
@endif

@if($medecin)
<div class="alert alert-info">
    Planning de <strong>{{ $medecin->user->name }}</strong>
    ({{ $medecin->specialite }}) —
    Semaine du {{ $debutSemaine->format('d/m/Y') }}
    au {{ $debutSemaine->copy()->endOfWeek()->format('d/m/Y') }}
</div>

<div class="card shadow-sm">
    <div class="card-body p-0" style="overflow-x: auto;">
        <table class="table table-bordered mb-0" style="min-width:900px;">
            <thead class="table-dark">
                <tr>
                    <th style="width:80px">Heure</th>
                    @php
                        $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
                    @endphp
                    @foreach($jours as $i => $jour)
                    <th class="text-center">
                        {{ $jour }}<br>
                        <small class="fw-normal">
                            {{ $debutSemaine->copy()->addDays($i)->format('d/m') }}
                        </small>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $horaires = [];
                    $h = \Carbon\Carbon::parse('10:00');
                    while ($h < \Carbon\Carbon::parse('13:00')) {
                        $horaires[] = $h->format('H:i');
                        $h->addMinutes(30);
                    }
                    $horaires[] = 'pause';
                    $h = \Carbon\Carbon::parse('14:30');
                    while ($h < \Carbon\Carbon::parse('18:30')) {
                        $horaires[] = $h->format('H:i');
                        $h->addMinutes(30);
                    }
                @endphp

                @foreach($horaires as $heure)
                    @if($heure === 'pause')
                        <tr class="table-secondary">
                            <td colspan="7" class="text-center text-muted py-1 small">
                                🍽️ Pause déjeuner (13h00 — 14h30)
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="fw-bold text-center bg-light small">{{ $heure }}</td>
                            @for($i = 0; $i < 6; $i++)
                                @php
                                    $dateJour = $debutSemaine->copy()
                                        ->addDays($i)->format('Y-m-d');
                                    $rdv = $rendezvous->first(function($r)
                                        use ($dateJour, $heure) {
                                        return \Carbon\Carbon::parse($r->date_heure)
                                            ->format('Y-m-d') === $dateJour &&
                                            \Carbon\Carbon::parse($r->date_heure)
                                            ->format('H:i') === $heure;
                                    });
                                    // Samedi pas d'après-midi
                                    $estSamediApresMidi = $i === 5 &&
                                        $heure >= '14:30';
                                @endphp
                                <td class="p-1"
                                    style="{{ $estSamediApresMidi ? 'background:#f0f0f0;' : '' }}">
                                    @if($estSamediApresMidi)
                                        <small class="text-muted">—</small>
                                    @elseif($rdv)
                                        @php
                                            $colors = [
                                                'en_attente' => 'warning',
                                                'accepte'    => 'success',
                                                'refuse'     => 'danger',
                                                'termine'    => 'primary',
                                                'annule'     => 'dark',
                                            ];
                                            $c = $colors[$rdv->statut] ?? 'secondary';
                                            // Cliquable si accepté ou terminé
                                            $cliquable = in_array($rdv->statut,
                                                ['accepte','termine']);
                                        @endphp
                                        @if($cliquable)
                                            <a href="{{ route('historique.show', $rdv->patient_id) }}"
                                               class="text-decoration-none">
                                        @endif
                                        <div class="badge bg-{{ $c }} w-100 p-2
                                                    text-wrap text-start
                                                    {{ $cliquable ? 'cursor-pointer' : '' }}"
                                             style="font-size:11px;">
                                            <strong>{{ $rdv->patient->user->name }}</strong><br>
                                            <span>{{ $rdv->statut }}</span>
                                            @if($cliquable)
                                                <br><small>🗂️ Voir dossier</small>
                                            @endif
                                        </div>
                                        @if($cliquable)
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Légende -->
<div class="mt-3 d-flex gap-2 flex-wrap">
    <span class="badge bg-warning text-dark">En attente</span>
    <span class="badge bg-success">Accepté</span>
    <span class="badge bg-danger">Refusé</span>
    <span class="badge bg-primary">Terminé (cliquer = dossier)</span>
    <span class="badge bg-dark">Annulé</span>
</div>
@else
<div class="alert alert-warning">Aucun médecin trouvé.</div>
@endif
@endsection