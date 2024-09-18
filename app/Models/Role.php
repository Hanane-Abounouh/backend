<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
    ];

    // Si vous avez des relations, définissez-les ici
    // Par exemple, une relation avec le modèle User
    public function utilisateurs()
    {
        return $this->hasMany(User::class);
    }
}
