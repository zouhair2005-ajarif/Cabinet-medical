@extends('layouts.app')
@section('title', 'Gestion Utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill"></i> Gestion des Utilisateurs</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvel Utilisateur
    </a>
</div>

<!-- Onglets par rôle -->
<ul class="nav nav-tabs mb-4" id="userTabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tab-tous">
            👥 Tous
            <span class="badge bg-secondary">{{ $users->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-medecins">
            🩺 Médecins
            <span class="badge bg-success">
                {{ $users->where('role','medecin')->count() }}
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-patients">
            👤 Patients
            <span class="badge bg-primary">
                {{ $users->where('role','patient')->count() }}
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-secretaires">
            📋 Secrétaires
            <span class="badge bg-warning text-dark">
                {{ $users->where('role','secretaire')->count() }}
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-admins">
            ⚙️ Admins
            <span class="badge bg-danger">
                {{ $users->where('role','admin')->count() }}
            </span>
        </a>
    </li>
</ul>

<div class="tab-content">
    @foreach([
        'tous'       => $users,
        'medecins'   => $users->where('role','medecin'),
        'patients'   => $users->where('role','patient'),
        'secretaires'=> $users->where('role','secretaire'),
        'admins'     => $users->where('role','admin'),
    ] as $tab => $liste)
    <div class="tab-pane fade {{ $tab === 'tous' ? 'show active' : '' }}"
         id="tab-{{ $tab }}">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liste as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $colors = [
                                        'admin'      => 'danger',
                                        'medecin'    => 'success',
                                        'secretaire' => 'warning text-dark',
                                        'patient'    => 'primary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colors[$user->role] ?? 'secondary' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted small">Vous</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Aucun utilisateur dans cette catégorie.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection