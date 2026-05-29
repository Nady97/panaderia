<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('nota_compra_productos');
        
        Schema::create('nota_compra_productos', function (Blueprint $table) {
            $table->id();
            
            // ❌ NO usar unsigned - usar integer normal
            $table->integer('nota_compra_id');
            $table->integer('producto_id');
            
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_compra_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            
            // Índices
            $table->index('nota_compra_id');
            $table->index('producto_id');
            $table->index(['nota_compra_id', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nota_compra_productos');
    }
};