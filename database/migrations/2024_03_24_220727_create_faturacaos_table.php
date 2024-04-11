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
        Schema::create('faturacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id');
            $table->foreignId('user_id');
            $table->decimal('valor', 10, 2)->nullable();
            $table->date('data');
            $table->string('funcao');
            $table->string('observacoes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturacaos');
    }
};
