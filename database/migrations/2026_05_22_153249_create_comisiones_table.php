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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estilista_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cita_id')->nullable()->constrained('citas')->onDelete('set null');
            $table->decimal('monto_servicio', 10, 2);
            $table->decimal('porcentaje_comision', 5, 2);
            $table->decimal('monto_comision', 10, 2);
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
            $table->timestamp('fecha_calculo')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
