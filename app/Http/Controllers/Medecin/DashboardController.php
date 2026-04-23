<?php
namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $medecin = Auth::user()->medecin;

        // RDV du jour (acceptés)
        $rendezvous = RendezVous::where('medecin_id', $medecin->id)
            ->whereDate('date_heure', today())
            ->whereIn('statut', ['accepte', 'en_attente'])
            ->with('patient.user')
            ->orderBy('date_heure')
            ->get();

        // RDV en attente (notifications)
        $rdvEnAttente = RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', 'en_attente')
            ->with('patient.user')
            ->orderBy('date_heure')
            ->get();

        // RDV cette semaine
        $rdvSemaine = RendezVous::where('medecin_id', $medecin->id)
            ->whereBetween('date_heure', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();

        // RDV terminés total
        $rdvTermines = RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', 'termine')
            ->count();

        return view('medecin.dashboard', compact(
            'rendezvous', 'rdvEnAttente', 'rdvSemaine', 'rdvTermines'
        ));
    }
}