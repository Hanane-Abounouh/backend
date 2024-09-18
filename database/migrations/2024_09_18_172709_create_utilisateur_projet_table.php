<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateurProjetTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateur_projet', function (Blueprint $table) {
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Clé étrangère vers la table roles
            $table->boolean('invitation_acceptee')->default(false);
            $table->primary(['utilisateur_id', 'projet_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateur_projet');
    }
}

