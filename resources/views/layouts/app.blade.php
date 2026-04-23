<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet Médical — @yield('title', 'Accueil')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
          rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1d23 0%, #212529 100%);
            width: 230px;
            min-width: 230px;
            padding: 20px 10px;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i { margin-right: 8px; }
        .sidebar-section {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 15px 5px;
            margin-top: 10px;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            min-height: calc(100vh - 56px);
        }
        .topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
    </style>
</head>
<body>

<!-- Topbar -->
<nav class="navbar topbar px-4 d-flex justify-content-between">
    <a class="navbar-brand fw-bold text-dark" href="#">
        🏥 Cabinet Médical
    </a>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">
            👤 <strong>{{ Auth::user()->name }}</strong>
        </span>
        @php
            $roleColors = [
                'admin'      => 'danger',
                'medecin'    => 'success',
                'secretaire' => 'warning',
                'patient'    => 'primary',
            ];
            $roleColor = $roleColors[Auth::user()->role] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $roleColor }}">
            {{ Auth::user()->role }}
        </span>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
        </form>
    </div>
</nav>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">

            {{-- ADMIN --}}
            @if(Auth::user()->role === 'admin')
                <div class="sidebar-section">Principal</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <div class="sidebar-section">Gestion</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                       href="{{ route('patients.index') }}">
                        <i class="bi bi-people"></i> Patients
                    </a>
                </li>

                <!-- ✅ NOUVEAU PLANNING -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.planning') ? 'active' : '' }}"
                       href="{{ route('rendezvous.planning') }}">
                        <i class="bi bi-calendar-week"></i> Planning
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.index') ? 'active' : '' }}"
                       href="{{ route('rendezvous.index') }}">
                        <i class="bi bi-calendar-check"></i> Rendez-vous
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="bi bi-person-gear"></i> Utilisateurs
                    </a>
                </li>

            {{-- MEDECIN --}}
            @elseif(Auth::user()->role === 'medecin')
                <div class="sidebar-section">Principal</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medecin.dashboard') ? 'active' : '' }}"
                       href="{{ route('medecin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <div class="sidebar-section">Médical</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                       href="{{ route('patients.index') }}">
                        <i class="bi bi-people"></i> Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.index') ? 'active' : '' }}"
                       href="{{ route('rendezvous.index') }}">
                        <i class="bi bi-calendar-check"></i> Rendez-vous
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.planning') ? 'active' : '' }}"
                       href="{{ route('rendezvous.planning') }}">
                        <i class="bi bi-calendar-week"></i> Mon Planning
                    </a>
                </li>

            {{-- SECRETAIRE --}}
            @elseif(Auth::user()->role === 'secretaire')
                <div class="sidebar-section">Principal</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('secretaire.dashboard') ? 'active' : '' }}"
                       href="{{ route('secretaire.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <div class="sidebar-section">Gestion</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                       href="{{ route('patients.index') }}">
                        <i class="bi bi-people"></i> Patients
                    </a>
                </li>

                <!-- ✅ NOUVEAU PLANNING -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.planning') ? 'active' : '' }}"
                       href="{{ route('rendezvous.planning') }}">
                        <i class="bi bi-calendar-week"></i> Planning
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.index') ? 'active' : '' }}"
                       href="{{ route('rendezvous.index') }}">
                        <i class="bi bi-calendar-check"></i> Rendez-vous
                    </a>
                </li>

            {{-- PATIENT --}}
            @elseif(Auth::user()->role === 'patient')
                <div class="sidebar-section">Mon Espace</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}"
                       href="{{ route('patient.dashboard') }}">
                        <i class="bi bi-house"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rendezvous.*') ? 'active' : '' }}"
                       href="{{ route('rendezvous.index') }}">
                        <i class="bi bi-calendar-check"></i> Mes RDV
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('historique.*') ? 'active' : '' }}"
                       href="{{ route('historique.index') }}">
                        <i class="bi bi-file-medical"></i> Mon Dossier
                    </a>
                </li>
            @endif

        </ul>
    </div>

    <!-- Contenu -->
    <div class="main-content">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>

