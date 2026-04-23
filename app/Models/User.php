<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function medecin()
    {
        return $this->hasOne(Medecin::class);
    }

    public function secretaire()
    {
        return $this->hasOne(Secretaire::class);
    }

    // Vérification du rôle
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMedecin()
    {
        return $this->role === 'medecin';
    }

    public function isSecretaire()
    {
        return $this->role === 'secretaire';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }
}