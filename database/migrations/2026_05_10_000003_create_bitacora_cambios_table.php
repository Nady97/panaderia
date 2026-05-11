<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('bitacora_cambios')) {
      Schema::create('bitacora_cambios', function (Blueprint $table) {
        $table->id();
        $table->string('usuario_codigo', 10)->nullable()->collation('utf8mb4_general_ci');
        $table->string('modulo', 50);
        $table->string('accion', 20);
        $table->string('descripcion', 255)->nullable();
        $table->json('datos_antes')->nullable();
        $table->json('datos_despues')->nullable();
        $table->timestamp('created_at')->useCurrent();

        $table->index('usuario_codigo');
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('bitacora_cambios');
  }
};
