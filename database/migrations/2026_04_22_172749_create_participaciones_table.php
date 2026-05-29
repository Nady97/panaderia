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
        if (!Schema::hasTable('participaciones')) {
            Schema::create('participaciones', function (Blueprint $table) {
                $table->string('usuario_codigo', 10);
                $table->unsignedBigInteger('produccion_id'); // Match producciones.id

                $table->enum('rol_en_turno', ['maestro_panadero', 'ayudante', 'hornero', 'limpieza'])->nullable()->default('ayudante');
                $table->decimal('horas_trabajadas', 4, 2)->nullable();
                $table->string('observaciones_empleado', 150)->nullable();

                $table->primary(['usuario_codigo', 'produccion_id']);
                $table->index('produccion_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participaciones');
    }
};
