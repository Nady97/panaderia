<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ✅ Verificar si la tabla ya existe
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('slug')->nullable();
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        } else {
            // ✅ Si ya existe, solo agregar columnas que falten
            Schema::table('roles', function (Blueprint $table) {
                if (!Schema::hasColumn('roles', 'slug')) {
                    $table->string('slug')->nullable();
                }
                if (!Schema::hasColumn('roles', 'activo')) {
                    $table->boolean('activo')->default(true);
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};