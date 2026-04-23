<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable = [
        'consultation_id', 'medicaments',
        'instructions', 'date', 'exporter_pdf'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}