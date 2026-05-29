<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('solicitudes_produccion')) {
      Schema::create('solicitudes_produccion', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('produccion_id');
        $table->enum('tipo_urgencia', ['normal', 'urgente', 'muy_urgente'])->default('normal');
        $table->text('motivo_urgencia')->nullable();
        $table->enum('estado', ['solicitada', 'aprobada', 'rechazada', 'completada'])->default('solicitada');
        $table->string('usuario_solicitante', 10);
        $table->string('usuario_aprobador', 10)->nullable();
        $table->timestamp('fecha_solicitud')->useCurrent();
        $table->timestamp('fecha_aprobacion')->nullable();
        $table->text('comentario_aprobador')->nullable();

        $table->index('produccion_id');
        $table->index('usuario_solicitante');
        $table->index('estado');
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('solicitudes_produccion');
  }
};
