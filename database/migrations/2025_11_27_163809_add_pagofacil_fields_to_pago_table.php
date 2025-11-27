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
        Schema::table('pago', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('inscripcion_id');
            $table->string('payment_number')->nullable()->after('transaction_id');
            $table->string('estado_pago', 20)->default('pendiente')->after('payment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payment_number', 'estado_pago']);
        });
    }
};
