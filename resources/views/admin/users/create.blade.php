@extends('layouts.app')
@section('title', 'Nouvel Utilisateur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-plus"></i> Nouvel Utilisateur</h2>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rôle *</label>
                    <select name="role" id="role" class="form-select" required
                            onchange="toggleSpecialite()">
                        <option value="">-- Choisir --</option>
                        <option value="admin">Administrateur</option>
                        <option value="medecin">Médecin</option>
                        <option value="secretaire">Secrétaire</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="specialiteDiv" style="display:none">
                    <label class="form-label">Spécialité (médecin)</label>
                    <input type="text" name="specialite" class="form-control"
                           placeholder="Ex: Cardiologie">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Créer
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleSpecialite() {
    const role = document.getElementById('role').value;
    document.getElementById('specialiteDiv').style.display =
        role === 'medecin' ? 'block' : 'none';
}
</script>
@endsection