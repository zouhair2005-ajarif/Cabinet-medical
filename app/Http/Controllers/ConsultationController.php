<?php
namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultationController extends Controller
{
    public function create(RendezVous $rendezvous)
    {
        $rendezvous->load([
            'patient.user',
            'patient.dossierMedical',
            'medecin.user'
        ]);
        return view('consultations.create', compact('rendezvous'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rendezvous_id' => 'required|exists:rendezvous,id',
            'compte_rendu'  => 'required|string',
            'observations'  => 'nullable|string',
            'medicaments'   => 'nullable|string',
            'instructions'  => 'nullable|string',
        ]);

        $rendezvous = RendezVous::findOrFail($request->rendezvous_id);
        $dossier    = $rendezvous->patient->dossierMedical;

        if (!$dossier) {
            return back()->withErrors(['error' => 'Dossier médical introuvable.']);
        }

        $consultation = Consultation::create([
            'rendezvous_id'    => $request->rendezvous_id,
            'dossier_medical_id' => $dossier->id,
            'compte_rendu'     => $request->compte_rendu,
            'observations'     => $request->observations,
            'date'             => now()->toDateString(),
        ]);

        if ($request->filled('medicaments')) {
            Ordonnance::create([
                'consultation_id' => $consultation->id,
                'medicaments'     => $request->medicaments,
                'instructions'    => $request->instructions,
                'date'            => now()->toDateString(),
                'exporter_pdf'    => false,
            ]);
        }

        $rendezvous->update(['statut' => 'termine']);

        return redirect()->route('rendezvous.index')
            ->with('success', '✅ Consultation enregistrée avec succès !');
    }

    public function exportPdf($id)
    {
        $ordonnance = Ordonnance::with([
            'consultation.rendezvous.patient.user',
            'consultation.rendezvous.medecin.user',
            'consultation.rendezvous.medecin',
            'consultation.rendezvous.patient',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('consultations.ordonnance_pdf', compact('ordonnance'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('ordonnance_' . $id . '.pdf');
    }
}