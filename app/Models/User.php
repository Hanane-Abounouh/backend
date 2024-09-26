<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessor pour générer l'avatar
    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            return $this->attributes['avatar'];
        }
    
        $letters = strtoupper(substr($this->name, 0, 2));
        $avatar = "https://ui-avatars.com/api/?name={$letters}&background=random";
        $this->attributes['avatar'] = $avatar;
        $this->save();  // Save avatar if it wasn't set initially
    
        return $avatar;
    }

    // Mutator pour le mot de passe
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relation avec le modèle Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation avec les tâches créées par l'utilisateur.
     */
    public function tachesCrees()
    {
        return $this->hasMany(Tache::class, 'cree_par'); // Les tâches créées par l'utilisateur
    }

    /**
     * Relation avec les tâches assignées à l'utilisateur.
     */
    public function tachesAssignees()
    {
        return $this->hasMany(Tache::class, 'assigne_a'); // Les tâches assignées à l'utilisateur
    }
}
