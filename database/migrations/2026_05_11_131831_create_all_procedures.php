<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS actualizar_precio_producto");
        DB::unprepared("
            CREATE PROCEDURE actualizar_precio_producto(IN p_nombre VARCHAR(50), IN p_nuevo_precio DECIMAL(12,2))
            BEGIN
                UPDATE productos SET precio_venta = p_nuevo_precio WHERE nombre = p_nombre;
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS agregar_producto");
        DB::unprepared("
            CREATE PROCEDURE agregar_producto(IN p_nombre VARCHAR(50), IN p_precio DECIMAL(12,2), IN p_categoria_id INTEGER, IN p_stock_minimo INTEGER)
            BEGIN
                INSERT INTO productos (nombre, precio_venta, categoria_id, stock_minimo, stock_actual, es_producido, estado) 
                VALUES (p_nombre, p_precio, p_categoria_id, p_stock_minimo, 0, 1, 'activo'); 
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS eliminar_producto");
        DB::unprepared("
            CREATE PROCEDURE eliminar_producto(IN p_nombre VARCHAR(50))
            BEGIN
                DELETE FROM productos WHERE nombre = p_nombre; 
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS generar_compra_producto");
        DB::unprepared("
            CREATE PROCEDURE generar_compra_producto(IN p_nota_compra_id INTEGER, IN p_producto_id INTEGER, IN p_cantidad INTEGER, IN p_precio DECIMAL(12,2))
            BEGIN
                DECLARE v_total DECIMAL(12,2);
                SET v_total = p_cantidad * p_precio;
                INSERT INTO compra_producto (nota_compra_id, producto_id, cantidad, precio, total)
                VALUES (p_nota_compra_id, p_producto_id, p_cantidad, p_precio, v_total);
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS insertar_usuario");
        DB::unprepared("
            CREATE PROCEDURE insertar_usuario(IN p_codigo VARCHAR(10), IN p_nombre VARCHAR(50), IN p_sexo CHAR(1), IN p_password VARCHAR(250), IN p_telefono VARCHAR(15), IN p_rol_id INTEGER)
            BEGIN
                INSERT INTO usuarios (codigo, nombre, sexo, password, telefono, rol_id) 
                VALUES (p_codigo, p_nombre, p_sexo, p_password, p_telefono, p_rol_id); 
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS actualizar_precio_producto");
        DB::unprepared("DROP PROCEDURE IF EXISTS agregar_producto");
        DB::unprepared("DROP PROCEDURE IF EXISTS eliminar_producto");
        DB::unprepared("DROP PROCEDURE IF EXISTS generar_compra_producto");
        DB::unprepared("DROP PROCEDURE IF EXISTS insertar_usuario");
    }
};