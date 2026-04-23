@extends('layouts.app')
@section('title', 'Dashboard Médecin')

@section('content')
<h2 class="mb-4">
    <i class="bi bi-heart-pulse"></i> Bonjour, {{ Auth::user()->name }}
</h2>

{{-- Notifications RDV en attente --}}
@if($rdvEnAttente->count() > 0)
<div class="alert alert-warning border-start border-warning border-4 mb-4">
    <h5 class="alert-heading">
        <i class="bi bi-bell-fill"></i>
        {{ $rdvEnAttente->count() }} rendez-vous en attente de votre réponse
    </h5>
    <hr>
    @foreach($rdvEnAttente as $rdv)
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span>
            👤 <strong>{{ $rdv->patient->user->name }}</strong> —
            📅 {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
            @if($rdv->motif) — {{ $rdv->motif }} @endif
        </span>
        <div class="d-flex gap-2">
            <form action="{{ route('rendezvous.accepter', $rdv) }}" method="POST">
                @csrf
                <button class="btn btn-success btn-sm">
                    <i class="bi bi-check-lg"></i> Accepter
                </button>
            </form>
            <form action="{{ route('rendezvous.refuser', $rdv) }}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm">
                    <i class="bi bi-x-lg"></i> Refuser
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Cartes stats --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h6><i class="bi bi-hourglass"></i> En attente</h6>
                <h2>{{ $rdvEnAttente->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h6><i class="bi bi-calendar-check"></i> RDV aujourd'hui</h6>
                <h2>{{ $rendezvous->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h6><i class="bi bi-calendar-week"></i> Cette semaine</h6>
                <h2>{{ $rdvSemaine }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body">
                <h6><i class="bi bi-check2-all"></i> Terminés</h6>
                <h2>{{ $rdvTermines }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Rendez-vous du jour --}}
<div class="card shadow-sm">
    <div class="card-header fw-bold bg-success text-white">
        <i class="bi bi-calendar"></i> Mes rendez-vous du jour
    </div>
    <div class="card-body p-0">
        @if($rendezvous->isEmpty())
            <div class="p-4 text-muted text-center">
                <i class="bi bi-calendar-x fs-1"></i>
                <p class="mt-2">Aucun rendez-vous aujourd'hui.</p>
            </div>
        @else
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Heure</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rendezvous as $rdv)
                    <tr>
                        <td>{{ $rdv->patient->user->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }}
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
                            <span class="badge bg-{{ $c }}">
                                {{ $rdv->statut }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('rendezvous.show', $rdv) }}"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($rdv->statut === 'accepte' && !$rdv->consultation)
                                <a href="{{ route('consultations.create', $rdv) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-file-medical"></i> Consultation
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection