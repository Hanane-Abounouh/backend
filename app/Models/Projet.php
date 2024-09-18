<?php
// app/Models/Projet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'date_debut', 'date_fin', 'créé_par'];

    public function utilisateurs()
    {
        return $this->belongsToMany(User::class, 'utilisateur_projet')->withPivot('invitation_acceptee');
    }

    public function taches()
    {
        return $this->hasMany(Tache::class);
    }
}
