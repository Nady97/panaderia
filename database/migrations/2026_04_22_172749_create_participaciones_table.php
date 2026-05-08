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
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                $table->string('usuario_codigo', 10)->collation('utf8mb4_general_ci');
                $table->integer('produccion_id'); // Match producciones.id which is an int

                $table->enum('rol_en_turno', ['maestro_panadero', 'ayudante', 'hornero', 'limpieza'])->nullable()->default('ayudante');
                $table->decimal('horas_trabajadas', 4, 2)->nullable();
                $table->string('observaciones_empleado', 150)->nullable();

                $table->primary(['usuario_codigo', 'produccion_id']);
            });

            // Crear Foreign Keys separadas
            Schema::table('participaciones', function (Blueprint $table) {
                $table->foreign('usuario_codigo')->references('codigo')->on('usuarios')->onDelete('restrict');
                $table->foreign('produccion_id')->references('id')->on('producciones')->onDelete('cascade');
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
