<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\DossierMedical;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $patient = $user->patient;
        $patient->load([
            'dossierMedical.consultations.rendezvous.medecin.user',
            'dossierMedical.consultations.ordonnance',
            'rendezvous.medecin.user',
        ]);

        return view('patient.historique', compact('patient'));
    }

    // Pour médecin qui consulte le dossier d'un patient
    public function show($patientId)
    {
        $patient = Patient::with([
            'user',
            'dossierMedical.consultations.rendezvous.medecin.user',
            'dossierMedical.consultations.ordonnance',
            'rendezvous.medecin.user',
        ])->findOrFail($patientId);

        return view('patient.dossier', compact('patient'));
    }

    public function updateDossier(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $dossier = $patient->dossierMedical;

        $request->validate([
            'antecedents'        => 'nullable|string',
            'allergies'          => 'nullable|string',
            'maladies_chroniques'=> 'nullable|string',
            'diagnostics'        => 'nullable|string',
        ]);

        $dossier->update([
            'antecedents'         => $request->antecedents,
            'allergies'           => $request->allergies,
            'maladies_chroniques' => $request->maladies_chroniques,
            'diagnostics'         => $request->diagnostics,
        ]);

        return back()->with('success', 'Dossier médical mis à jour !');
    }
}