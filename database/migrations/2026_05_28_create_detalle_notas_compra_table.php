<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('detalle_notas_compra')) {
      Schema::create('detalle_notas_compra', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('nota_compra_id');
        $table->unsignedBigInteger('insumo_id');
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 12, 2);
        $table->decimal('subtotal', 12, 2);

        $table->index(['nota_compra_id', 'insumo_id']);
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('detalle_notas_compra');
  }
};
