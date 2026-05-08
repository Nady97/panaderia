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
                $table->foreignId('rol_id')->nullable()->constrained('roles')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('usuarios', 'rol_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropForeign(['rol_id']);
                $table->dropColumn('rol_id');
            });
        }
    }
};