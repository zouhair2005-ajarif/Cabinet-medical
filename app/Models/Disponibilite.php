<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    protected $fillable = [
        'medecin_id', 'date_heure_debut', 'date_heure_fin', 'est_disponible'
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
}