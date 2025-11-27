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
        Schema::create('cursos_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('curso')->onDelete('cascade');
            $table->integer('dia_semana'); // 0=domingo, 1=lunes, ..., 6=sábado
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos_horarios');
    }
};
