<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Secretaire;
use App\Models\Patient;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,medecin,secretaire,patient',
            'specialite' => 'required_if:role,medecin',
            'telephone'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Créer le profil selon le rôle
        if ($request->role === 'medecin') {
            Medecin::create([
                'user_id'    => $user->id,
                'specialite' => $request->specialite,
                'telephone'  => $request->telephone,
            ]);
        } elseif ($request->role === 'secretaire') {
            Secretaire::create([
                'user_id'   => $user->id,
                'telephone' => $request->telephone,
            ]);
        } elseif ($request->role === 'patient') {
            $patient = Patient::create([
                'user_id'   => $user->id,
                'telephone' => $request->telephone,
            ]);
            DossierMedical::create([
                'patient_id'    => $patient->id,
                'date_creation' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur créé avec succès !');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur supprimé !');
    }
}