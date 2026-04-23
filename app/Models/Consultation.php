<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'rendezvous_id', 'dossier_medical_id',
        'compte_rendu', 'observations', 'date'
    ];

    public function rendezvous()
    {
        return $this->belongsTo(RendezVous::class, 'rendezvous_id');
    }

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class);
    }

    public function ordonnance()
    {
        return $this->hasOne(Ordonnance::class);
    }
}