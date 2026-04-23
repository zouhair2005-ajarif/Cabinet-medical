@extends('layouts.app')
@section('title', 'Modifier Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Modifier Patient</h2>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $patient->user->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $patient->user->email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control"
                           value="{{ $patient->telephone }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control"
                           value="{{ $patient->date_naissance }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="2">{{ $patient->adresse }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Modifier
            </button>
        </form>
    </div>
</div>
@endsection