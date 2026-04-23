<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $patient    = Auth::user()->patient;
        $rendezvous = RendezVous::where('patient_id', $patient->id)
            ->with('medecin.user')
            ->orderBy('date_heure', 'desc')
            ->get();

        return view('patient.dashboard', compact('rendezvous', 'patient'));
    }
}