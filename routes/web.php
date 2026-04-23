<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Medecin\DashboardController as MedecinDashboard;
use App\Http\Controllers\Secretaire\DashboardController as SecretaireDashboard;
use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\HistoriqueController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\ConsultationController;

Route::get('/', function () {
    if (Auth::check()) {
        return match(Auth::user()->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'medecin'    => redirect()->route('medecin.dashboard'),
            'secretaire' => redirect()->route('secretaire.dashboard'),
            'patient'    => redirect()->route('patient.dashboard'),
            default      => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('auth')->get('/dashboard', function () {
    return match(Auth::user()->role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'medecin'    => redirect()->route('medecin.dashboard'),
        'secretaire' => redirect()->route('secretaire.dashboard'),
        'patient'    => redirect()->route('patient.dashboard'),
        default      => redirect()->route('login'),
    };
})->name('dashboard');

require __DIR__.'/auth.php';

// Admin
Route::middleware(['auth','admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUser::class);
    });

// Médecin
Route::middleware(['auth','medecin'])
    ->prefix('medecin')->name('medecin.')
    ->group(function () {
        Route::get('/dashboard', [MedecinDashboard::class, 'index'])->name('dashboard');
    });

// Secrétaire
Route::middleware(['auth','secretaire'])
    ->prefix('secretaire')->name('secretaire.')
    ->group(function () {
        Route::get('/dashboard', [SecretaireDashboard::class, 'index'])->name('dashboard');
    });

// Patient
Route::middleware(['auth','patient'])
    ->prefix('patient')->name('patient.')
    ->group(function () {
        Route::get('/dashboard', [PatientDashboard::class, 'index'])->name('dashboard');
    });

// Modules communs
Route::middleware('auth')->group(function () {

    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('patients-search', [PatientController::class, 'search'])
        ->name('patients.search');

    // Historique médical
    Route::get('historique', [HistoriqueController::class, 'index'])
        ->name('historique.index');
    Route::get('historique/{patient}', [HistoriqueController::class, 'show'])
        ->name('historique.show');
    Route::put('historique/{patient}', [HistoriqueController::class, 'updateDossier'])
        ->name('historique.update');

    // Rendez-vous — routes spéciales AVANT resource
    Route::get('rendezvous/creneaux', [RendezVousController::class, 'getCreneaux'])
        ->name('rendezvous.creneaux');
    Route::get('rendezvous/planning', [RendezVousController::class, 'planning'])
        ->name('rendezvous.planning');
    Route::post('rendezvous/{rendezvous}/accepter',
        [RendezVousController::class, 'accepter'])->name('rendezvous.accepter');
    Route::post('rendezvous/{rendezvous}/refuser',
        [RendezVousController::class, 'refuser'])->name('rendezvous.refuser');
    Route::post('rendezvous/{rendezvous}/annuler',
        [RendezVousController::class, 'annuler'])->name('rendezvous.annuler');

    Route::resource('rendezvous', RendezVousController::class);

    // Consultations
    Route::get('consultations/create/{rendezvous}',
        [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('consultations',
        [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('consultations/pdf/{id}',
        [ConsultationController::class, 'exportPdf'])->name('consultations.pdf');
});