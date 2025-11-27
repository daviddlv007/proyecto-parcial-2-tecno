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
        Schema::create('visitas_pagina', function (Blueprint $table) {
            $table->id();
            $table->string('ruta')->unique();
            $table->bigInteger('contador')->default(0);
            $table->timestamp('fecha_ultimo_acceso')->nullable();
            $table->timestamps();
            
            $table->index('contador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas_pagina');
    }
};
