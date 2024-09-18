<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash; // Import du Hash ici

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    // Attributs remplissables
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'avatar',
    ];

    // Attributs protégés contre l'exposition
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attributs castés en types
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
        $this->save();
    
        return $avatar;
    }
    
    

    // Mutator pour le mot de passe
    public function setPasswordAttribute($value)
    {
        // Utilisation de Hash pour sécuriser le mot de passe
        $this->attributes['password'] = Hash::make($value);
    }

    // Relation avec le modèle Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
