<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Secretaire;
use App\Models\Disponibilite;
use App\Models\DossierMedical;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer l'Admin
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 2. Créer des Médecins
        $medecinUser1 = User::create([
            'name'     => 'Dr. Ahmed Benali',
            'email'    => 'medecin1@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'medecin',
        ]);
        Medecin::create([
            'user_id'    => $medecinUser1->id,
            'specialite' => 'Cardiologie',
            'telephone'  => '0612345678',
        ]);

        $medecinUser2 = User::create([
            'name'     => 'Dr. Fatima Zahra',
            'email'    => 'medecin2@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'medecin',
        ]);
        Medecin::create([
            'user_id'    => $medecinUser2->id,
            'specialite' => 'Pédiatrie',
            'telephone'  => '0623456789',
        ]);

        // 3. Créer une Secrétaire
        $secUser = User::create([
            'name'     => 'Sara Alami',
            'email'    => 'secretaire@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'secretaire',
        ]);
        Secretaire::create([
            'user_id'   => $secUser->id,
            'telephone' => '0634567890',
        ]);

        // 4. Créer des Patients
        $patientUser1 = User::create([
            'name'     => 'Mohamed Tazi',
            'email'    => 'patient1@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'patient',
        ]);
        $patient1 = Patient::create([
            'user_id'        => $patientUser1->id,
            'telephone'      => '0645678901',
            'date_naissance' => '1990-05-15',
            'adresse'        => 'Rue Hassan II, Marrakech',
        ]);
        DossierMedical::create([
            'patient_id'    => $patient1->id,
            'antecedents'   => 'Hypertension',
            'allergies'     => 'Pénicilline',
            'date_creation' => now(),
        ]);

        $patientUser2 = User::create([
            'name'     => 'Amina Ouali',
            'email'    => 'patient2@cabinet.com',
            'password' => Hash::make('password'),
            'role'     => 'patient',
        ]);
        $patient2 = Patient::create([
            'user_id'        => $patientUser2->id,
            'telephone'      => '0656789012',
            'date_naissance' => '1985-08-22',
            'adresse'        => 'Avenue Mohammed V, Casablanca',
        ]);
        DossierMedical::create([
            'patient_id'    => $patient2->id,
            'antecedents'   => 'Diabète type 2',
            'allergies'     => 'Aucune',
            'date_creation' => now(),
        ]);

        // 5. Créer des disponibilités pour les médecins
        $medecin1 = Medecin::where('user_id', $medecinUser1->id)->first();
        Disponibilite::create([
            'medecin_id'      => $medecin1->id,
            'date_heure_debut' => '2026-04-20 09:00:00',
            'date_heure_fin'   => '2026-04-20 12:00:00',
            'est_disponible'   => true,
        ]);
        Disponibilite::create([
            'medecin_id'      => $medecin1->id,
            'date_heure_debut' => '2026-04-21 14:00:00',
            'date_heure_fin'   => '2026-04-21 17:00:00',
            'est_disponible'   => true,
        ]);

        $this->command->info('✅ Données de test créées avec succès !');
        $this->command->info('Admin     : admin@cabinet.com / password');
        $this->command->info('Médecin   : medecin1@cabinet.com / password');
        $this->command->info('Secrétaire: secretaire@cabinet.com / password');
        $this->command->info('Patient   : patient1@cabinet.com / password');
    }
}