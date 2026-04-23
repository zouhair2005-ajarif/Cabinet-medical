<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'antecedents',
        'allergies',
        'maladies_chroniques',
        'diagnostics',
        'date_creation'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function documents()
    {
        return $this->hasMany(DocumentMedical::class);
    }
}