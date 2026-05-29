<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ✅ Verificar si la columna ya existe
        if (!Schema::hasColumn('usuarios', 'rol_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->unsignedBigInteger('rol_id')->nullable();
                $table->index('rol_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('usuarios', 'rol_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('rol_id');
            });
        }
    }
};
