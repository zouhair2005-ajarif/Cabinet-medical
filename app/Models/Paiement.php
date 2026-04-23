<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'rendezvous_id', 'montant', 'date', 'methode', 'statut'
    ];

    public function rendezvous()
    {
        return $this->belongsTo(RendezVous::class);
    }
}