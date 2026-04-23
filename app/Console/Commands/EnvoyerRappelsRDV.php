<?php
namespace App\Console\Commands;

use App\Mail\RappelRDV;
use App\Models\RendezVous;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnvoyerRappelsRDV extends Command
{
    protected $signature   = 'rdv:rappels';
    protected $description = 'Envoyer des rappels email pour les RDV de demain';

    public function handle()
    {
        $demain = Carbon::tomorrow();

        $rendezvous = RendezVous::whereDate('date_heure', $demain)
            ->where('statut', 'accepte')
            ->with(['patient.user', 'medecin.user', 'medecin'])
            ->get();

        $count = 0;
        foreach ($rendezvous as $rdv) {
            try {
                Mail::to($rdv->patient->user->email)
                    ->send(new RappelRDV($rdv));
                $count++;
            } catch (\Exception $e) {
                $this->error("Erreur pour RDV #{$rdv->id}: " . $e->getMessage());
            }
        }

        $this->info("✅ {$count} rappel(s) envoyé(s) avec succès !");
    }
}