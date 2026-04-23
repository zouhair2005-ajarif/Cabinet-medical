<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Secretaire;
use App\Models\RendezVous;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'    => Patient::count(),
            'total_medecins'    => Medecin::count(),
            'total_secretaires' => Secretaire::count(),
            'total_rendezvous'  => RendezVous::count(),
            'rdv_aujourd_hui'   => RendezVous::whereDate('date_heure', today())->count(),
            'rdv_en_attente'    => RendezVous::where('statut', 'en_attente')->count(),
            'rdv_accepte'       => RendezVous::where('statut', 'accepte')->count(),
            'rdv_refuse'        => RendezVous::where('statut', 'refuse')->count(),
            'rdv_termine'       => RendezVous::where('statut', 'termine')->count(),
            'rdv_annule'        => RendezVous::where('statut', 'annule')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}