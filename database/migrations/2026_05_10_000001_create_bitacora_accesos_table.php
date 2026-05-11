<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('bitacora_accesos')) {
      Schema::create('bitacora_accesos', function (Blueprint $table) {
        $table->id();
        $table->string('usuario_codigo', 10)->collation('utf8mb4_general_ci');
        $table->string('accion', 20);
        $table->string('ip_address', 45)->nullable();
        $table->string('user_agent', 255)->nullable();
        $table->timestamp('created_at')->useCurrent();

        $table->index('usuario_codigo');
      });
    }

    Schema::table('bitacora_accesos', function (Blueprint $table) {
      if (!Schema::hasColumn('bitacora_accesos', 'usuario_codigo')) {
        return;
      }
      $table->foreign('usuario_codigo')
        ->references('codigo')
        ->on('usuarios')
        ->onDelete('restrict');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('bitacora_accesos');
  }
};
