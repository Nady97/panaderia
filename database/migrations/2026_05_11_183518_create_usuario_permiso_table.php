<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_permiso', function (Blueprint $table) {
            $table->string('usuario_codigo', 10);
            $table->unsignedBigInteger('permiso_id');
            $table->primary(['usuario_codigo', 'permiso_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_permiso');
    }
};