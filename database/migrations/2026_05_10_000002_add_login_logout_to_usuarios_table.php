<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('usuarios', function (Blueprint $table) {
      if (!Schema::hasColumn('usuarios', 'last_login_at')) {
        $table->timestamp('last_login_at')->nullable()->after('rol_id');
      }
      if (!Schema::hasColumn('usuarios', 'last_logout_at')) {
        $table->timestamp('last_logout_at')->nullable()->after('last_login_at');
      }
    });
  }

  public function down(): void
  {
    Schema::table('usuarios', function (Blueprint $table) {
      if (Schema::hasColumn('usuarios', 'last_logout_at')) {
        $table->dropColumn('last_logout_at');
      }
      if (Schema::hasColumn('usuarios', 'last_login_at')) {
        $table->dropColumn('last_login_at');
      }
    });
  }
};
