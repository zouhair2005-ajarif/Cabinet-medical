@extends('layouts.app')
@section('title', 'Modifier RDV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-event"></i> Modifier Rendez-vous</h2>
    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('rendezvous.update', $rendezvous) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Patient</label>
                    <input type="text" class="form-control"
                           value="{{ $rendezvous->patient->user->name }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Médecin</label>
                    <input type="text" class="form-control"
                           value="{{ $rendezvous->medecin->user->name }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date et Heure *</label>
                    <input type="datetime-local" name="date_heure" class="form-control"
                           value="{{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('Y-m-d\TH:i') }}"
                           required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statut *</label>
                    <select name="statut" class="form-select" required>
                        <option value="en_attente"
                            {{ $rendezvous->statut === 'en_attente' ? 'selected' : '' }}>
                            En attente
                        </option>
                        <option value="confirme"
                            {{ $rendezvous->statut === 'confirme' ? 'selected' : '' }}>
                            Confirmé
                        </option>
                        <option value="annule"
                            {{ $rendezvous->statut === 'annule' ? 'selected' : '' }}>
                            Annulé
                        </option>
                        <option value="termine"
                            {{ $rendezvous->statut === 'termine' ? 'selected' : '' }}>
                            Terminé
                        </option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Motif</label>
                    <input type="text" name="motif" class="form-control"
                           value="{{ $rendezvous->motif }}">
                </div>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Modifier
            </button>
        </form>
    </div>
</div>
@endsection