<?php
// database/seeders/RolesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Données à insérer dans la table roles
        $roles = [
            ['nom' => 'Admin', 'description' => 'Gestion globale. Accès à toutes les fonctionnalités et gestion des utilisateurs, projets, tâches, fichiers, messages, et notifications ...'],
            ['nom' => 'Créateur', 'description' => 'Peut créer des projets et gérer les utilisateurs et les tâches associées à ces projets ...'],
            ['nom' => 'Invité', 'description' => 'Peut créer, modifier, et marquer les tâches comme terminées ...'],
        ];

        // Insérer les données dans la table roles
        DB::table('roles')->insert($roles);
    }
}
