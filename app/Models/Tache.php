<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'date_limite', 'statut', 'priorite', 'projet_id', 'assigne_a', 'cree_par',
    ];

    /**
     * Relation avec l'utilisateur assigné à la tâche.
     */
    public function utilisateurAssigne()
    {
        return $this->belongsTo(User::class, 'assigne_a');
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
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Relation avec les commentaires.
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'tache_id'); // Assurez-vous que 'tache_id' correspond à votre colonne dans la table des commentaires
    }
}
