<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Corrige la tabla de sesiones para que sea compatible con el modelo Usuario
   * que usa 'codigo' como clave primaria en lugar de 'id'
   */
  public function up(): void
  {
    // Verificar si la tabla sessions existe
    if (Schema::hasTable('sessions')) {
      // Verificar si la tabla ya está con la estructura correcta
      $columns = Schema::getColumnListing('sessions');

      if (in_array('user_id', $columns)) {
        // Intentar remover la restricción de clave foránea si existe
        try {
          Schema::table('sessions', function (Blueprint $table) {
            if (Schema::hasColumns('sessions', ['user_id'])) {
              // Intenta dropear la foreign key si existe
              DB::statement('ALTER TABLE sessions DROP FOREIGN KEY IF EXISTS sessions_user_id_foreign');
            }
          });
        } catch (\Exception $e) {
          // Si no existe la restricción, continuamos
        }

        // Reconstruir la tabla sessions con la estructura correcta
        Schema::dropIfExists('sessions');
      }
    }

    if (!Schema::hasTable('sessions')) {
      Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->string('user_id')->nullable()->index();  // Cambiar a string para 'codigo'
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sessions');

    // Restaurar la estructura original
    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }
};
