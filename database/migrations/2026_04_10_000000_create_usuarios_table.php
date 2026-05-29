<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasTable('usuarios')) {
      Schema::create('usuarios', function (Blueprint $table) {
        $table->string('codigo', 10)->primary()->collation('utf8mb4_general_ci');
        $table->string('nombre', 255);
        $table->string('email', 255)->nullable()->unique();
        $table->string('password', 255);
        $table->string('telefono', 15)->nullable();
        $table->string('direccion', 255)->nullable();
        $table->string('descripcion', 255)->nullable();
        $table->string('sexo', 1)->nullable();
        $table->string('imagen', 255)->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->timestamp('last_login_at')->nullable();
        $table->timestamp('last_logout_at')->nullable();
        $table->unsignedBigInteger('rol_id')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable();

        $table->index('rol_id');
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_general_ci';
      });
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('usuarios');
  }
};
