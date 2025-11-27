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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('ruta', 255);
            $table->string('icono', 500)->nullable();
            $table->integer('orden')->default(999);
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->foreign('rol_id')->references('id')->on('rol')->onDelete('cascade');
            $table->index(['rol_id', 'activo', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
