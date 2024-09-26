<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTachesTable extends Migration
{
    public function up()
    {
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['backlog', 'a faire', 'en cours', 'termine', 'bloque'])->default('backlog'); // Statut initial
            $table->enum('priorite', ['basse', 'moyenne', 'elevee'])->nullable();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigne_a')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('taches');
    }
}
