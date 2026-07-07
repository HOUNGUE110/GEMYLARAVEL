<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Assure-toi que cette ligne est présente
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Gère le hachage automatique du mot de passe
    ];

    /**
     * Un utilisateur peut publier plusieurs marqueurs sur la carte.
     */
    public function markers(): HasMany
    {
        return $this->hasMany(Marker::class);
    }
}