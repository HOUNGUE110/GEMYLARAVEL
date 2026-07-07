<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marker extends Model
{
    use HasFactory;

    // Définition des champs autorisés lors de la création/modification (Mass Assignment)
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'categorie',
        'latitude',
        'longitude',
        'contact'
    ];

    /**
     * Un marqueur appartient à un unique utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}