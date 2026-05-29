<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('notas_compra')) {
      Schema::create('notas_compra', function (Blueprint $table) {
        $table->id();
        $table->string('numero_nota', 20)->unique();
        $table->string('proveedor_codigo', 10);
        $table->date('fecha_nota');
        $table->date('fecha_entrega_estimada')->nullable();
        $table->decimal('monto_total', 12, 2)->default(0);
        $table->enum('estado', ['borrador', 'confirmada', 'recibida', 'cancelada'])->default('borrador');
        $table->text('observaciones')->nullable();
        $table->string('usuario_codigo', 10);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable();

        $table->index('proveedor_codigo');
        $table->index('usuario_codigo');
        $table->index('estado');
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('notas_compra');
  }
};
