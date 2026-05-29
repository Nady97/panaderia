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
        if (Schema::hasTable('producciones')) {
            // Si la tabla ya existe, agregamos las columnas que faltan
            Schema::table('producciones', function (Blueprint $table) {
                if (!Schema::hasColumn('producciones', 'usuario_codigo')) {
                    $table->string('usuario_codigo', 10)->collation('utf8mb4_general_ci')->nullable();
                }
                if (!Schema::hasColumn('producciones', 'receta_id')) {
                    $table->integer('receta_id')->nullable();
                }
            });
        } else {
            // Si no existe, la creamos con toda la estructura
            Schema::create('producciones', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                // El ID DEBE ser integer auto_increment, NO bigint, porque la tabla 'participaciones' 
                // ya lo tiene referenciado como int(11). Si ponemos bigint, MySQL tira Errno 150 en la creacion misma!
                $table->integer('id', true);

                // Campos según el Modelo Produccion
                $table->string('lote_codigo', 20)->nullable();
                $table->string('descripcion', 150)->nullable();
                $table->date('fecha_programada');
                $table->dateTime('hora_inicio')->nullable();
                $table->dateTime('hora_fin')->nullable();
                $table->enum('estado', ['planificado', 'en_proceso', 'completado', 'fallido'])->default('planificado');
                $table->decimal('cantidad_producida', 12, 2)->default(0);
                $table->text('observaciones_calidad')->nullable();

                // Llaves foráneas con el tipo exacto
                $table->integer('receta_id'); // recetas.id es int(11)
                $table->string('usuario_codigo', 10)->collation('utf8mb4_general_ci'); // usuarios.codigo es varchar(10)

                $table->timestamp('created_at')->useCurrent();

                $table->index('receta_id');
                $table->index('usuario_codigo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producciones', function (Blueprint $table) {
            //
        });
    }
};
