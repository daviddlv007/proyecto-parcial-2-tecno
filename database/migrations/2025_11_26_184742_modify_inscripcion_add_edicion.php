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
        Schema::table('inscripcion', function (Blueprint $table) {
            $table->foreignId('curso_edicion_id')->nullable()->after('curso_id')->constrained('curso_edicion')->onDelete('cascade');
            $table->dropColumn(['fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::table('inscripcion', function (Blueprint $table) {
            $table->dropForeign(['curso_edicion_id']);
            $table->dropColumn('curso_edicion_id');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }
};
