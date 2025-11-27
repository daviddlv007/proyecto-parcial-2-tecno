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
        Schema::table('curso_horario', function (Blueprint $table) {
            $table->foreignId('curso_edicion_id')->nullable()->after('curso_id')->constrained('curso_edicion')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('curso_horario', function (Blueprint $table) {
            $table->dropForeign(['curso_edicion_id']);
            $table->dropColumn('curso_edicion_id');
        });
    }
};
