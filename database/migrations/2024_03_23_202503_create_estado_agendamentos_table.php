<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estado_agendamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cor');
            $table->timestamps();
        });

        Schema::table('agendas', function (Blueprint $table) {
            $table->foreignId('estado_agendamento_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_agendamentos');
    }
};
