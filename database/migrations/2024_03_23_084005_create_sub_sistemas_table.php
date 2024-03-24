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
        Schema::create('sub_sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sub_sistema_utente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_sistema_id')->constrained();
            $table->foreignId('utente_id')->constrained();
            $table->timestamps();
        });

        //add to agenda

        Schema::table('agendas', function (Blueprint $table) {
            $table->foreignId('sub_sistema_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sistema_utente');
        Schema::dropIfExists('sub_sistemas');
    }
};
