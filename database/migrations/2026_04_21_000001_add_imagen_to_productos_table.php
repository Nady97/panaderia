<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (!Schema::hasColumn('productos', 'imagen')) {
      Schema::table('productos', function (Blueprint $table) {
        $table->string('imagen')->nullable()->after('estado');
      });
    }
  }

  public function down(): void
  {
    if (Schema::hasColumn('productos', 'imagen')) {
      Schema::table('productos', function (Blueprint $table) {
        $table->dropColumn('imagen');
      });
    }
  }
};
