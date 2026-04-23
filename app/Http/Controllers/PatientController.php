<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    // Liste tous les patients
    public function index()
    {
        $patients = Patient::with('user')->orderBy('created_at', 'desc')->get();
        return view('patients.index', compact('patients'));
    }

    // Formulaire création
    public function create()
    {
        return view('patients.create');
    }

    // Enregistrer nouveau patient
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'telephone'      => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse'        => 'nullable|string',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'patient',
        ]);

        // Créer le profil patient
        $patient = Patient::create([
            'user_id'        => $user->id,
            'telephone'      => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'adresse'        => $request->adresse,
        ]);

        // Créer automatiquement un dossier médical vide
        DossierMedical::create([
            'patient_id'    => $patient->id,
            'antecedents'   => '',
            'allergies'     => '',
            'date_creation' => now(),
        ]);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient créé avec succès !');
    }

    // Afficher un patient
    public function show(Patient $patient)
    {
        $patient->load('user', 'dossierMedical', 'rendezvous.medecin.user');
        return view('patients.show', compact('patient'));
    }

    // Formulaire modification
    public function edit(Patient $patient)
    {
        $patient->load('user');
        return view('patients.edit', compact('patient'));
    }

    // Enregistrer modification
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $patient->user_id,
            'telephone'      => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse'        => 'nullable|string',
        ]);

        $patient->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $patient->update([
            'telephone'      => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'adresse'        => $request->adresse,
        ]);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient modifié avec succès !');
    }

    // Supprimer patient
    public function destroy(Patient $patient)
    {
        $patient->user->delete(); // Supprime aussi le patient (cascade)
        return redirect()->route('patients.index')
                         ->with('success', 'Patient supprimé avec succès !');
    }

    // Recherche patients
    public function search(Request $request)
    {
        $query = $request->get('q');
        $patients = Patient::with('user')
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%");
            })
            ->orWhere('telephone', 'like', "%$query%")
            ->get();

        return view('patients.index', compact('patients', 'query'));
    }
}