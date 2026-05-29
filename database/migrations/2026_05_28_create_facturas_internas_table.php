<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('facturas_internas')) {
      Schema::create('facturas_internas', function (Blueprint $table) {
        $table->id();
        $table->string('numero_factura', 20)->unique();
        $table->unsignedBigInteger('nota_compra_id');
        $table->date('fecha_emision');
        $table->date('fecha_vencimiento');
        $table->decimal('monto_total', 12, 2);
        $table->decimal('impuestos', 12, 2)->default(0);
        $table->decimal('monto_neto', 12, 2);
        $table->enum('estado', ['emitida', 'pagada', 'anulada'])->default('emitida');
        $table->text('notas')->nullable();
        $table->string('usuario_codigo', 10);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable();

        $table->index('nota_compra_id');
        $table->index('usuario_codigo');
        $table->index('estado');
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('facturas_internas');
  }
};
