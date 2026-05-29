<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('permiso_rol')) {
      Schema::create('permiso_rol', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('rol_id');
        $table->unsignedBigInteger('permiso_id');
        $table->timestamps();

        $table->unique(['rol_id', 'permiso_id']);
        $table->index(['rol_id', 'permiso_id']);
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('permiso_rol');
  }
};
