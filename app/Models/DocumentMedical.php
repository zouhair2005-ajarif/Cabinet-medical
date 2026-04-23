<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentMedical extends Model
{
    protected $table = 'documents_medicaux';

    protected $fillable = [
        'dossier_medical_id',
        'nom',
        'fichier',
        'type'
    ];

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class);
    }
}