<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $rendezvous = RendezVous::with(['patient.user', 'medecin.user'])
            ->orderBy('date_heure', 'desc')
            ->take(10)
            ->get();

        $total_patients = Patient::count();

        return view('secretaire.dashboard', compact('rendezvous', 'total_patients'));
    }
}