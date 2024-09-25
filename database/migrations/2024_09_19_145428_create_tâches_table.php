<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTâchesTable extends Migration
{
    public function up()
    {
        Schema::create('tâches', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_limite')->nullable();
            $table->enum('statut', [
                'backlog', 
                'à faire', 
                'en cours', 
                'terminé', 
                'bloqué'
            ])->default('backlog'); // Statut initial défini comme 'backlog'
            $table->enum('priorité', ['basse', 'moyenne', 'élevée'])->nullable();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigné_a')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('créé_par')->constrained('users')->onDelete('cascade');
          
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tâches');
    }
}
