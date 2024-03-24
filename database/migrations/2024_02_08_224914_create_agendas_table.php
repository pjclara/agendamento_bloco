<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utente_id')->constrained();
            $table->foreignId('cirurgiao_id')->constrained('users');
            $table->foreignId('ajudante_id')->nullable()->constrained('users');
            $table->foreignId('anestesista_id')->nullable()->constrained('users');
            $table->dateTime('data');
            $table->string('observacoes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // many interventions
        Schema::create('agenda_intervencao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained();
            $table->foreignId('intervencao_id')->constrained();
            $table->timestamps();
        });

        // many patologies
        Schema::create('agenda_patologia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained();
            $table->foreignId('patologia_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_intervencao');
        Schema::dropIfExists('agenda_patologia');
        Schema::dropIfExists('agendas');
    }
};
