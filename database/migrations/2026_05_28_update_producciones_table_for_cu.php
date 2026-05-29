<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up(): void
  {
    if (Schema::hasTable('producciones')) {
      // Usar SQL raw para evitar problemas con columnas inexistentes
      if (!Schema::hasColumn('producciones', 'usuario_responsable_codigo')) {
        DB::statement('ALTER TABLE producciones ADD COLUMN usuario_responsable_codigo VARCHAR(10) COLLATE utf8mb4_general_ci NULL COMMENT "Usuario responsable del proceso de producción"');
        DB::statement('CREATE INDEX idx_user_resp ON producciones(usuario_responsable_codigo)');
      }

      if (!Schema::hasColumn('producciones', 'fecha_inicio_real')) {
        DB::statement('ALTER TABLE producciones ADD COLUMN fecha_inicio_real DATETIME NULL');
      }

      if (!Schema::hasColumn('producciones', 'fecha_fin_real')) {
        DB::statement('ALTER TABLE producciones ADD COLUMN fecha_fin_real DATETIME NULL');
      }
    }
  }

  public function down(): void
  {
    if (Schema::hasTable('producciones')) {
      Schema::table('producciones', function (Blueprint $table) {
        if (Schema::hasColumn('producciones', 'usuario_responsable_codigo')) {
          $table->dropColumn('usuario_responsable_codigo');
        }
        if (Schema::hasColumn('producciones', 'fecha_inicio_real')) {
          $table->dropColumn('fecha_inicio_real');
        }
        if (Schema::hasColumn('producciones', 'fecha_fin_real')) {
          $table->dropColumn('fecha_fin_real');
        }
      });
    }
  }
};
