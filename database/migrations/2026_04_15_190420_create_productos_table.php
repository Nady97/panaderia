<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('productos')) {
            Schema::create('productos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->decimal('precio_venta', 10, 2);
                $table->decimal('precio_costo', 10, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->integer('stock_minimo')->default(5);
                $table->string('estado')->default('activo');

                //  RELACIÓN CON PROVEEDORES
                $table->unsignedBigInteger('proveedor_id')->nullable();
                ////$table->foreign('proveedor_id')
                 //   ->references('id_proveedor')
                  //  ->on('proveedores')
                  //  ->onDelete('set null');
                  //  RELACIÓN CON CATEGORÍAS (si existe)
                $table->unsignedBigInteger('categoria_id')->nullable();
                $table->foreign('categoria_id')
                    ->references('id')
                    ->on('categorias')
                    ->onDelete('set null');
                $table->timestamps();
                });
        }
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};