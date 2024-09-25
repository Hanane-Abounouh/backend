<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'chemin',
        'version',
        'projet_id',
        'téléversé_par',
    ];

    // Relation avec le modèle Projet
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    // Relation avec le modèle User
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'téléversé_par');
    }
}
