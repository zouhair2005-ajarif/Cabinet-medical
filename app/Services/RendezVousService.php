<?php
namespace App\Services;

use App\Models\RendezVous;
use Carbon\Carbon;

class RendezVousService
{
    const DUREE_RDV = 30;

    const HORAIRES = [
        'matin_debut'     => '10:00',
        'matin_fin'       => '13:00',
        'apresmidi_debut' => '14:30',
        'apresmidi_fin'   => '18:30',
    ];

    public function estDansHoraires(Carbon $dateHeure): bool
    {
        if ($dateHeure->dayOfWeek === Carbon::SUNDAY) return false;

        $date   = $dateHeure->format('Y-m-d');
        $finRdv = $dateHeure->copy()->addMinutes(self::DUREE_RDV);

        if ($dateHeure->dayOfWeek === Carbon::SATURDAY) {
            $debut = Carbon::parse($date . ' ' . self::HORAIRES['matin_debut']);
            $fin   = Carbon::parse($date . ' ' . self::HORAIRES['matin_fin']);
            return $dateHeure >= $debut && $finRdv <= $fin;
        }

        $matinDebut  = Carbon::parse($date . ' ' . self::HORAIRES['matin_debut']);
        $matinFin    = Carbon::parse($date . ' ' . self::HORAIRES['matin_fin']);
        $apresDebut  = Carbon::parse($date . ' ' . self::HORAIRES['apresmidi_debut']);
        $apresFin    = Carbon::parse($date . ' ' . self::HORAIRES['apresmidi_fin']);

        $dansMatin     = $dateHeure >= $matinDebut && $finRdv <= $matinFin;
        $dansApresMidi = $dateHeure >= $apresDebut && $finRdv <= $apresFin;

        return $dansMatin || $dansApresMidi;
    }

    public function aConflit(int $medecinId, Carbon $dateHeure, ?int $excludeId = null): bool
    {
        $fin = $dateHeure->copy()->addMinutes(self::DUREE_RDV);

        $query = RendezVous::where('medecin_id', $medecinId)
            ->whereNotIn('statut', ['annule', 'refuse'])
            ->where(function($q) use ($dateHeure, $fin) {
                $q->where(function($q2) use ($dateHeure, $fin) {
                    $q2->where('date_heure', '<', $fin)
                       ->whereRaw(
                           "DATE_ADD(date_heure, INTERVAL 30 MINUTE) > ?",
                           [$dateHeure]
                       );
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getCreneauxDisponibles(int $medecinId, string $date): array
    {
        $dateCarbon = Carbon::parse($date);

        if ($dateCarbon->dayOfWeek === Carbon::SUNDAY) return [];

        $maintenant = Carbon::now();
        $creneaux   = [];

        $plages = [
            ['debut' => self::HORAIRES['matin_debut'], 'fin' => self::HORAIRES['matin_fin']],
        ];

        if ($dateCarbon->dayOfWeek !== Carbon::SATURDAY) {
            $plages[] = [
                'debut' => self::HORAIRES['apresmidi_debut'],
                'fin'   => self::HORAIRES['apresmidi_fin']
            ];
        }

        foreach ($plages as $plage) {
            $current = Carbon::parse($date . ' ' . $plage['debut']);
            $fin     = Carbon::parse($date . ' ' . $plage['fin']);

            while ($current->copy()->addMinutes(self::DUREE_RDV) <= $fin) {
                // ✅ Ignorer les créneaux déjà passés si c'est aujourd'hui
                if ($dateCarbon->isToday() && $current->lte($maintenant)) {
                    $current->addMinutes(self::DUREE_RDV);
                    continue;
                }

                if (!$this->aConflit($medecinId, $current->copy())) {
                    $creneaux[] = $current->format('H:i');
                }
                $current->addMinutes(self::DUREE_RDV);
            }
        }

        return $creneaux;
    }
}