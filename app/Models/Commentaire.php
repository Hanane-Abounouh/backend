<?php

// app/Models/Commentaire.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = ['contenu', 'tâche_id', 'utilisateur_id'];

    public function tâche()
    {
        return $this->belongsTo(Tâche::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }
}
