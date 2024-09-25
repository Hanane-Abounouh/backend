<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tâche extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'date_limite', 'statut', 'priorité', 'projet_id', 'assigné_a', 'créé_par',
    ];

    /**
     * Relation avec l'utilisateur assigné à la tâche.
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'assigné_a');
    }

    /**
     * Relation avec le projet.
     */
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé la tâche.
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'créé_par');
    }

    /**
     * Relation avec les commentaires.
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'tâche_id'); // Assurez-vous que 'tâche_id' correspond à votre colonne dans la table des commentaires
    }
}
