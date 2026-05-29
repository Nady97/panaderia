<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Creamos la estructura básica SIN la llave foránea inicial
        if (!Schema::hasTable('producciones')) {
            Schema::create('producciones', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                $table->id();
                // Solamente creamos la columna, asegurando que comparta la misma collation
                $table->string('proveedor_codigo', 10)->collation('utf8mb4_general_ci');

                $table->integer('cantidad');
                $table->date('fecha_produccion');
                $table->enum('estado', ['En Proceso', 'Completado', 'Cancelado'])->default('En Proceso');
                $table->text('observaciones')->nullable();

                $table->timestamps();

                $table->index('proveedor_codigo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};
