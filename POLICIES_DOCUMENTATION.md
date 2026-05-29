# Sistema de Políticas de Autorización (Cycle 1 - Hardening)

## Descripción General

Se ha implementado un sistema robusto de **Políticas de Autorización** (Policies) para proteger los controladores críticos del sistema. Este sistema funciona en **3 niveles**:

1. **Nivel 1**: Database - Permisos almacenados en tabla `permisos` (ya existentes)
2. **Nivel 2**: Model Policies - Lógica de autorización por modelo en `app/Policies/*`
3. **Nivel 3**: Controller - Validación mediante `authorize()` en los métodos

## Políticas Implementadas

### 1. NotaCompraPolicy (`app/Policies/NotaCompraPolicy.php`)

Controla acceso a Notas de Compra (órdenes de proveedores).

**Métodos disponibles:**
- `viewAny()` - Ver lista de notas (requiere `notas_compra.view`)
- `view($nota)` - Ver detalle de nota (requiere `notas_compra.view`)
- `create()` - Crear nueva nota (requiere `notas_compra.create`)
- `update($nota)` - Editar nota (requiere `notas_compra.edit` + estado = `solicitado`)
- `delete($nota)` - Eliminar nota (requiere `notas_compra.delete` + estado = `solicitado`)
- `markAsReceived($nota)` - Marcar recibida (requiere `notas_compra.edit` + estado en `['solicitado', 'en_transito']`)
- `addItems($nota)` - Agregar insumos/productos (requiere `notas_compra.edit` + estado = `solicitado`)

**Reglas de negocio:**
- Solo se pueden editar/eliminar notas en estado `solicitado`
- Los ítems solo se agregan mientras la nota esté solicitada
- La recepción solo ocurre en estados específicos

---

### 2. ProductoPolicy (`app/Policies/ProductoPolicy.php`)

Controla acceso a gestión de Productos.

**Métodos disponibles:**
- `viewAny()` - Ver lista de productos (requiere `productos.view`)
- `view($producto)` - Ver detalle (requiere `productos.view`)
- `create()` - Crear producto (requiere `productos.create`)
- `update($producto)` - Editar producto (requiere `productos.edit`)
- `delete($producto)` - Eliminar producto (requiere `productos.delete` + estado ≠ `descontinuado`)
- `updateStock($producto)` - Actualizar stock (requiere `productos.edit`)
- `export()` - Exportar lista (requiere `productos.view`)

**Reglas de negocio:**
- No se pueden eliminar productos descontinuados (seguridad de datos)
- Actualizar stock solo si tienes permiso de edición

---

### 3. ProduccionPolicy (`app/Policies/ProduccionPolicy.php`)

Controla acceso a Órdenes de Producción.

**Métodos disponibles:**
- `viewAny()` - Ver lista (requiere `produccion.view`)
- `view($produccion)` - Ver detalle (requiere `produccion.view`)
- `create()` - Crear orden (requiere `produccion.create`)
- `update($produccion)` - Editar orden (requiere `produccion.edit` + estado en `['planificado', 'en_proceso']`)
- `delete($produccion)` - Eliminar orden (requiere `produccion.delete` + estado = `planificado`)
- `startProduction($produccion)` - Iniciar producción (requiere `produccion.edit` + estado = `planificado`)
- `completeProduction($produccion)` - Completar producción (requiere `produccion.edit` + estado = `en_proceso`)
- `recordWaste($produccion)` - Registrar desperdicio (requiere `produccion.edit` + estado en `['en_proceso', 'completado']`)
- `updateQuality($produccion)` - Actualizar QA (requiere `produccion.edit`)

**Reglas de negocio:**
- Solo órdenes planificadas se pueden eliminar
- Transiciones de estado controladas (planificado → en_proceso → completado)

---

### 4. FacturaInternaPolicy (`app/Policies/FacturaInternaPolicy.php`)

Controla acceso a Facturas Internas (ventas).

**Métodos disponibles:**
- `viewAny()` - Ver lista (requiere `facturas_internas.view`)
- `view($factura)` - Ver detalle (requiere `facturas_internas.view`)
- `create()` - Crear factura (requiere `facturas_internas.create`)
- `update($factura)` - Editar factura (requiere `facturas_internas.edit` + estado ≠ en `['cancelada', 'anulada']`)
- `delete($factura)` - Eliminar factura (requiere `facturas_internas.delete` + estado ≠ `anulada`)
- `generatePDF()` - Descargar PDF (requiere `facturas_internas.view`)
- `cancel($factura)` - Anular factura (requiere `facturas_internas.edit` + estado ≠ `anulada`)
- `recordPayment($factura)` - Registrar pago (requiere `facturas_internas.edit`)

**Reglas de negocio:**
- Facturas anuladas no se pueden modificar
- PDF solo descargable por quienes ven facturas

---

### 5. ProveedorPolicy (`app/Policies/ProveedorPolicy.php`)

Controla acceso a gestión de Proveedores.

**Métodos disponibles:**
- `viewAny()` - Ver lista (requiere `proveedores.view`)
- `view($proveedor)` - Ver detalle (requiere `proveedores.view`)
- `create()` - Crear proveedor (requiere `proveedores.create`)
- `update($proveedor)` - Editar proveedor (requiere `proveedores.edit`)
- `delete($proveedor)` - Eliminar proveedor (requiere `proveedores.delete` + estado = `activo`)
- `toggleStatus($proveedor)` - Activar/desactivar (requiere `proveedores.edit`)
- `viewHistory($proveedor)` - Ver historial de compras (requiere `proveedores.view`)
- `export()` - Exportar lista (requiere `proveedores.view`)

**Reglas de negocio:**
- Solo proveedores activos se pueden "eliminar" (soft delete concept)
- Historial de compras visible solo para quienes ven proveedores

---

## Cómo Funcionan las Policies

### Flujo de Autorización

```
Petición HTTP → Controller → authorize() → Policy
                                    ↓
                            hasPermission() en Usuario
                                    ↓
                            Consulta tabla permisos
                                    ↓
                            true/false
```

### Ejemplo en NotaCompraController

```php
public function update(Request $request, NotaCompra $notaCompra)
{
    $this->authorize('update', $notaCompra);  // ← Verifica policy
    
    // Si la policy devuelve false:
    // → Laravel lanza AuthorizationException (403 Forbidden)
    
    // Si devuelve true:
    // → Continúa la lógica del método
    $notaCompra->update($request->validated());
}
```

### Validación en Policy

```php
public function update(Usuario $user, NotaCompra $notaCompra): bool
{
    // 1. Valida permiso general
    if (!$user->hasPermission('notas_compra.edit')) {
        return false;
    }
    
    // 2. Valida regla de negocio (solo si estado = solicitado)
    if ($notaCompra->estado !== 'solicitado') {
        return false;
    }
    
    return true;
}
```

---

## Controladores Protegidos

| Controller | Métodos | Políticas |
|-----------|---------|-----------|
| **NotaCompraController** | 10 | NotaCompraPolicy |
| **FacturaInternaController** | 5 | FacturaInternaPolicy |
| **ProductoController** | 7 | ProductoPolicy |
| **ProduccionController** | 7 | ProduccionPolicy |
| **ProveedorController** | (próximo) | ProveedorPolicy |

---

## Registro en AuthServiceProvider

Archivo: `app/Providers/AuthServiceProvider.php`

```php
protected $policies = [
    NotaCompra::class => NotaCompraPolicy::class,
    Producto::class => ProductoPolicy::class,
    Produccion::class => ProduccionPolicy::class,
    FacturaInterna::class => FacturaInternaPolicy::class,
    Proveedor::class => ProveedorPolicy::class,
];
```

**Nota**: En Laravel 11, los providers se auto-registran. No necesita ser agregado a `bootstrap/app.php`.

---

## Cómo Probar las Políticas

### 1. Test Manual vía UI

1. Inicia sesión como **Usuario sin permisos** (rol con restricciones)
2. Intenta acceder a `/notas-compra/crear` → Debe mostrar **403 Forbidden**
3. Intenta acceder a `/productos/editar/{id}` → Debe mostrar **403 Forbidden**

### 2. Test vía Tinker

```bash
php artisan tinker

# Crear usuario de prueba
$user = \App\Models\Usuario::find('test_user');

# Verificar permiso
$user->hasPermission('notas_compra.create');  // true/false

# Verificar policy directamente
auth()->user()->can('create', \App\Models\NotaCompra::class);
```

### 3. Test de Blade Template

```blade
@can('create', \App\Models\NotaCompra::class)
    <a href="{{ route('notas_compra.create') }}">Crear Nota</a>
@else
    <p>No autorizado</p>
@endcan
```

---

## Errores Comunes

### Error: "This action is unauthorized"

**Causa**: Usuario no tiene el permiso requerido

**Solución**:
1. Verifica que el usuario tiene el permiso en tabla `permisos`
2. Verifica que el rol tiene el permiso asignado en `permiso_rol`

```bash
# Verificar en DB
SELECT * FROM permisos WHERE nombre = 'notas_compra.view';
SELECT * FROM permiso_rol WHERE permiso_id = X;
```

### Error: "Route model binding not found"

**Causa**: El ID en la URL no existe

**Solución**: Verifica que el recurso existe antes de acceder

---

## Próximos Pasos

### Fase 2: Completar Cobertura
- [ ] ProveedorController
- [ ] RecetaController
- [ ] InsumoController
- [ ] UsuarioController

### Fase 3: Audit & Logging
- [ ] Registrar intentos de acceso denegado
- [ ] Crear tabla de audit_logs
- [ ] Integrar con Spatie Activity Log

### Fase 4: Testing
- [ ] Test suite para policies
- [ ] Test de integración controller+policy
- [ ] Casos edge (estados límites)

---

## Referencias

- [Laravel Authorization (Policies)](https://laravel.com/docs/authorization#creating-policies)
- [Gate vs Policies](https://laravel.com/docs/authorization#via-the-can-method)
- [Best Practices](https://laravel.com/docs/authorization#model-policies)
