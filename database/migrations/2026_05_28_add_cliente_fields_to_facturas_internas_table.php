<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (Schema::hasTable('facturas_internas') && !Schema::hasColumn('facturas_internas', 'cliente_telefono')) {
      Schema::table('facturas_internas', function (Blueprint $table) {
        $table->string('cliente_telefono', 20)->nullable()->after('cliente_ci');
        $table->string('cliente_direccion', 255)->nullable()->after('cliente_telefono');
      });
    }
  }

  public function down(): void
  {
    Schema::table('facturas_internas', function (Blueprint $table) {
      $table->dropColumn(['cliente_telefono', 'cliente_direccion']);
    });
  }
};
