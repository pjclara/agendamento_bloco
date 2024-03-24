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
        Schema::create('intervencaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('intervencao_utente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervencao_id')->constrained();
            $table->foreignId('utente_id')->constrained();
            $table->timestamps();
        });

        Schema::create('patologia_intervencao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patologia_id')->constrained();
            $table->foreignId('intervencao_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patologia_intervencao');
        Schema::dropIfExists('intervencao_utente');
        Schema::dropIfExists('intervencaos');
    }
};
