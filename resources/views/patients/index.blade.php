@extends('layouts.app')
@section('title', 'Patients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Liste des Patients</h2>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Patient
    </a>
</div>

<!-- Recherche -->
<form action="{{ route('patients.search') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" class="form-control"
               placeholder="Rechercher par nom, email ou téléphone..."
               value="{{ $query ?? '' }}">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i> Rechercher
        </button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Date naissance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->user->name }}</td>
                    <td>{{ $patient->user->email }}</td>
                    <td>{{ $patient->telephone ?? '—' }}</td>
                    <td>{{ $patient->date_naissance ?? '—' }}</td>
                    <td>
                        <a href="{{ route('patients.show', $patient) }}"
                           class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('patients.edit', $patient) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('patients.destroy', $patient) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce patient ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun patient trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection