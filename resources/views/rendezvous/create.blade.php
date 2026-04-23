@extends('layouts.app')
@section('title', 'Nouveau RDV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-plus"></i> Nouveau Rendez-vous</h2>
    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p class="mb-0">❌ {{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('rendezvous.store') }}" method="POST">
            @csrf
            <div class="row">

                {{-- Si c'est un patient : on affiche son nom fixe --}}
                @if($estPatient)
                    <input type="hidden" name="patient_id" value="{{ $monPatient->id }}">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Patient</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ Auth::user()->name }}" disabled>
                    </div>
                @else
                    {{-- Admin/Secrétaire : choisir le patient --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Patient *</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Choisir un patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Médecin *</label>
                    <select name="medecin_id" id="medecin_id"
                            class="form-select" required>
                        <option value="">-- Choisir un médecin --</option>
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}">
                                {{ $medecin->user->name }}
                                — {{ $medecin->specialite }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date *</label>
                    <input type="date" name="date" id="date"
                           class="form-control"
                           min="{{ date('Y-m-d') }}" required>
                    <small class="text-muted">
                        ⚠️ Pas de RDV le dimanche. Samedi : matin seulement (10h-13h).
                    </small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Créneau disponible *</label>
                    <select name="heure" id="heure" class="form-select"
                            required disabled>
                        <option value="">
                            -- Choisissez médecin et date d'abord --
                        </option>
                    </select>
                    <div id="loadingCreneaux" class="text-primary mt-1"
                         style="display:none;">
                        ⏳ Chargement des créneaux...
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Motif de consultation</label>
                    <input type="text" name="motif" class="form-control"
                           placeholder="Ex: Consultation générale, suivi...">
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-clock"></i>
                <strong>Horaires :</strong>
                Matin 10h00→13h00 | Après-midi 14h30→18h30
                | Durée : <strong>30 min</strong>
            </div>

            <button type="submit" id="submitBtn"
                    class="btn btn-primary btn-lg" disabled>
                <i class="bi bi-check-circle"></i> Créer le rendez-vous
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const medecinSelect = document.getElementById('medecin_id');
const dateInput     = document.getElementById('date');
const heureSelect   = document.getElementById('heure');
const loading       = document.getElementById('loadingCreneaux');
const submitBtn     = document.getElementById('submitBtn');

function chargerCreneaux() {
    const medecinId = medecinSelect.value;
    const date      = dateInput.value;
    if (!medecinId || !date) return;

    const jour = new Date(date + 'T00:00:00').getDay();
    if (jour === 0) {
        heureSelect.innerHTML =
            '<option value="">❌ Pas de RDV le dimanche</option>';
        heureSelect.disabled = true;
        submitBtn.disabled   = true;
        return;
    }

    loading.style.display = 'block';
    heureSelect.disabled  = true;
    submitBtn.disabled    = true;
    heureSelect.innerHTML = '';

    fetch(`/rendezvous/creneaux?medecin_id=${medecinId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.creneaux.length === 0) {
                heureSelect.innerHTML =
                    '<option value="">❌ Aucun créneau disponible</option>';
                heureSelect.disabled = true;
                submitBtn.disabled   = true;
            } else {
                heureSelect.innerHTML =
                    '<option value="">-- Choisir un horaire --</option>';
                data.creneaux.forEach(h => {
                    heureSelect.innerHTML +=
                        `<option value="${h}">${h}</option>`;
                });
                heureSelect.disabled = false;
            }
        })
        .catch(() => {
            loading.style.display = 'none';
            heureSelect.innerHTML =
                '<option value="">❌ Erreur de connexion</option>';
        });
}

medecinSelect.addEventListener('change', chargerCreneaux);
dateInput.addEventListener('change', chargerCreneaux);
heureSelect.addEventListener('change', function() {
    submitBtn.disabled = !this.value;
});
</script>
@endsection