<?php
// app/Models/UtilisateurProjet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurProjet extends Model
{
    use HasFactory;

    protected $table = 'utilisateur_projet';

    protected $fillable = ['utilisateur_id', 'projet_id', 'role_id', 'invitation_acceptee'];

  // app/Models/UtilisateurProjet.php

public function utilisateur()
{
    return $this->belongsTo(User::class, 'utilisateur_id'); // Assurez-vous que 'utilisateur_id' est correct
}


    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
