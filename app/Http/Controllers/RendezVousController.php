<?php
namespace App\Http\Controllers;

use App\Mail\ConfirmationRDV;
use App\Mail\RDVAccepte;
use App\Mail\RDVRefuse;
use App\Mail\RDVAnnule;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Services\RendezVousService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RendezVousController extends Controller
{
    protected RendezVousService $rdvService;

    public function __construct(RendezVousService $rdvService)
    {
        $this->rdvService = $rdvService;
    }

    public function index(Request $request)
    {
        $user   = Auth::user();
        $filtre = $request->get('filtre', 'en_attente');

        $this->mettreAJourStatutsExpires();

        if ($user->role === 'patient') {
            $rendezvous = RendezVous::where('patient_id', $user->patient->id)
                ->with(['medecin.user'])
                ->orderBy('date_heure', 'desc')->get();

        } elseif ($user->role === 'medecin') {
            $query = RendezVous::where('medecin_id', $user->medecin->id)
                ->with(['patient.user']);
            if ($filtre !== 'tous') {
                $query->where('statut', $filtre);
            }
            $rendezvous = $query->orderBy('date_heure', 'desc')->get();

        } else {
            $rendezvous = RendezVous::with(['patient.user', 'medecin.user'])
                ->orderBy('date_heure', 'desc')->get();
        }

        return view('rendezvous.index', compact('rendezvous'));
    }

    private function mettreAJourStatutsExpires(): void
    {
        RendezVous::where('statut', 'accepte')
            ->where('date_heure', '<', now())
            ->update(['statut' => 'termine']);
    }

    public function create()
    {
        $medecins   = Medecin::with('user')->get();
        $user       = Auth::user();
        $estPatient = $user->role === 'patient';
        $patients   = $estPatient ? null : Patient::with('user')->get();
        $monPatient = $estPatient ? $user->patient : null;

        return view('rendezvous.create', compact(
            'medecins', 'patients', 'estPatient', 'monPatient'
        ));
    }

    public function getCreneaux(Request $request)
    {
        $request->validate([
            'medecin_id' => 'required|exists:medecins,id',
            'date'       => 'required|date',
        ]);

        $creneaux = $this->rdvService->getCreneauxDisponibles(
            (int) $request->medecin_id,
            $request->date
        );

        return response()->json(['creneaux' => $creneaux]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $patientId = $user->role === 'patient'
            ? $user->patient->id
            : $request->patient_id;

        $request->validate([
            'medecin_id' => 'required|exists:medecins,id',
            'date'       => 'required|date|after_or_equal:today',
            'heure'      => 'required',
            'motif'      => 'nullable|string|max:500',
        ]);

        $dateHeure = Carbon::parse($request->date . ' ' . $request->heure);

        if (!$this->rdvService->estDansHoraires($dateHeure)) {
            return back()->withErrors([
                'heure' => 'Ce créneau est hors des horaires du cabinet.'
            ])->withInput();
        }

        if ($this->rdvService->aConflit((int)$request->medecin_id, $dateHeure)) {
            return back()->withErrors([
                'heure' => 'Ce créneau est déjà réservé.'
            ])->withInput();
        }

        $rendezvous = RendezVous::create([
            'patient_id' => $patientId,
            'medecin_id' => $request->medecin_id,
            'date_heure' => $dateHeure,
            'motif'      => $request->motif,
            'statut'     => 'en_attente',
        ]);

        $rendezvous->load(['patient.user', 'medecin.user', 'medecin']);

        try {
            Mail::to($rendezvous->patient->user->email)
                ->send(new ConfirmationRDV($rendezvous));
        } catch (\Exception $e) {}

        return redirect()->route('rendezvous.index')
            ->with('success', '✅ Rendez-vous créé ! Email envoyé.');
    }

    public function show(RendezVous $rendezvous)
    {
        $rendezvous->load([
            'patient.user',
            'patient.dossierMedical.consultations.ordonnance',
            'medecin.user',
            'consultation.ordonnance'
        ]);
        return view('rendezvous.show', compact('rendezvous'));
    }

    public function edit(RendezVous $rendezvous)
    {
        // Patient peut modifier/annuler son RDV
        // Admin/Secrétaire peuvent modifier n'importe quel RDV
        $medecins = Medecin::with('user')->get();
        $patients = Patient::with('user')->get();
        return view('rendezvous.edit', compact('rendezvous', 'medecins', 'patients'));
    }

    public function update(Request $request, RendezVous $rendezvous)
    {
        $user = Auth::user();

        if ($user->role === 'patient') {
            // Patient peut seulement annuler avec commentaire
            $request->validate([
                'commentaire' => 'nullable|string|max:500',
            ]);
            $rendezvous->update([
                'statut'      => 'annule',
                'commentaire' => $request->commentaire,
            ]);

            $rendezvous->load(['patient.user', 'medecin.user', 'medecin']);
            try {
                Mail::to($rendezvous->patient->user->email)
                    ->send(new RDVAnnule($rendezvous, $request->commentaire ?? ''));
            } catch (\Exception $e) {}

        } else {
            $request->validate([
                'statut'      => 'required|in:en_attente,accepte,refuse,termine,annule',
                'motif'       => 'nullable|string',
                'commentaire' => 'nullable|string|max:500',
            ]);
            $rendezvous->update([
                'statut'      => $request->statut,
                'motif'       => $request->motif,
                'commentaire' => $request->commentaire,
            ]);
        }

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous mis à jour !');
    }

    public function destroy(RendezVous $rendezvous)
    {
        $rendezvous->delete();
        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous supprimé !');
    }

    public function planning(Request $request)
    {
        $user = Auth::user();

        // Admin/Secrétaire peuvent voir le planning de n'importe quel médecin
        if (in_array($user->role, ['admin', 'secretaire'])) {
            $medecins     = Medecin::with('user')->get();
            $medecinId    = $request->get('medecin_id', $medecins->first()?->id);
            $medecin      = Medecin::with('user')->find($medecinId);
        } else {
            $medecin      = $user->medecin;
            $medecinId    = $medecin->id;
            $medecins     = null;
        }

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine   = Carbon::now()->endOfWeek();

        $rendezvous = RendezVous::where('medecin_id', $medecinId)
            ->whereBetween('date_heure', [$debutSemaine, $finSemaine])
            ->with(['patient.user', 'consultation'])
            ->orderBy('date_heure')
            ->get();

        return view('rendezvous.planning', compact(
            'rendezvous', 'debutSemaine', 'medecin', 'medecins'
        ));
    }

    public function accepter(RendezVous $rendezvous)
    {
        $rendezvous->update(['statut' => 'accepte']);
        $rendezvous->load(['patient.user', 'medecin.user', 'medecin']);

        try {
            Mail::to($rendezvous->patient->user->email)
                ->send(new RDVAccepte($rendezvous));
        } catch (\Exception $e) {}

        return back()->with('success', '✅ RDV accepté ! Patient notifié.');
    }

    public function refuser(Request $request, RendezVous $rendezvous)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:500',
        ]);

        $rendezvous->update([
            'statut'      => 'refuse',
            'commentaire' => $request->commentaire,
        ]);

        $rendezvous->load(['patient.user', 'medecin.user', 'medecin']);

        try {
            Mail::to($rendezvous->patient->user->email)
                ->send(new RDVRefuse($rendezvous));
        } catch (\Exception $e) {}

        return back()->with('success', 'RDV refusé. Patient notifié.');
    }

    public function annuler(Request $request, RendezVous $rendezvous)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:500',
        ]);

        $rendezvous->update([
            'statut'      => 'annule',
            'commentaire' => $request->commentaire,
        ]);

        $rendezvous->load(['patient.user', 'medecin.user', 'medecin']);

        try {
            Mail::to($rendezvous->patient->user->email)
                ->send(new RDVAnnule($rendezvous, $request->commentaire ?? ''));
        } catch (\Exception $e) {}

        return back()->with('success', 'RDV annulé. Patient notifié par email.');
    }
}