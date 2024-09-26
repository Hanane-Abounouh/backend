<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'date_debut', 'date_fin', 'créé_par'];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'créé_par');
    }
    
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    public function utilisateurs()
    {
        return $this->belongsToMany(User::class, 'utilisateur_projet', 'projet_id', 'utilisateur_id')
            ->withPivot('role_id', 'invitation_acceptee');
    }
}
