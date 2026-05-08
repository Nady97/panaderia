<div align="center">
  <h1>🥐 Sistema de Gestión Integral para Panadería (ERP)</h1>
  <p>Una plataforma web moderna, unificada para la administración de inventarios, recetas y recursos humanos.</p>
</div>

---

## 🚀 Visión General

Este proyecto es una aplicación web estilo **SaaS (Software as a Service)** desarrollada para optimizar los procesos de una panadería profesional. Permite llevar un control riguroso y en tiempo real de productos, costos operativos, personal, libreta de recetas y listados de proveedores a través de una interfaz de usuario, intuitiva y eficiente.

## ✨ Características y Módulos Principales

El sistema goza de una arquitectura optimizada que protege el uso de memoria a través de **paginación del lado del servidor**, búsquedas mediante consultas nativas SQL y un diseño simétrico en cada módulo:

- 📦 **Gestión de Productos:** Control preciso de stock físico, alertas de inventario mínimo, cálculo de precios de costo frente a precios de venta pública.
- 🏷️ **Sistema de Categorías:** Agrupación y clasificación del catálogo de panes y postres, impulsado por identificadores automáticos (Slugs URL).
- 📖 **Libro de Recetas:** Fichas técnicas avanzadas para panaderos, controlando los tiempos de preparación, métricas de rendimiento por horneada y capacidad de descarga ágil de las recetas en formato PDF.
- 🚚 **Directorio de Proveedores:** Base de datos relacional para ubicar rápidamente contactos comerciales, empresas de distribución, números de NIT y líneas de atención.
- 👥 **Personal y Usuarios:** Módulo de control de acceso para los empleados de la panadería con niveles de roles y gestión de credenciales.

## 🛠️ Stack Tecnológico

- **Backend / Core:** Laravel 11 / 12 (PHP 8.2+)
- **Base de Datos:** MySQL
- **Frontend (UI/UX):** HTML5, Laravel Blade (Componentes unificados), Bootstrap 5 (Grillas y utilidades).
- **Estilos:** Vanilla CSS Modular (Manejo transversal de CSS Variables, Cero TailwindCSS para máximo rendimiento).
- **JavaScript:** Vanilla JS ES6+ Modularizado.
- **Alertas y Notificaciones:** SweetAlert2 (Integrado nativamente vía NPM).
- **Empaquetado de Assets:** Vite (Hot Module Replacement & Minificación).

## 🎨 Arquitectura de Diseño: Tema "Pan Francés"

adoptando el esquema visual **"Pan Francés"**, inspirado en la calidez de una panadería tradicional pero con la seriedad de un ERP corporativo:

- **Separation of Concerns (SoC):** Todo el código cliente se administra mediante
  esources/css y
  esources/js y es pre-compilado por Vite.
- **Modo Claro / Oscuro Nativo:** Implementación de un script Anti-FOUC (Flash of Unstyled Content) que respeta las preferencias del sistema operativo del usuario e invierte los contrastes de la paleta (Blanco cremoso vs Carbón chocolate) de forma instantánea y fluida.
- **Filtros Interactivos:** Búsqueda en tiempo real del lado del cliente optimizada con atributos data-\* y listeners modulares (Ej. productos.js).
- **Experiencia Homologada (SweetAlert2):** Interceptores globales en pp.js atrapan los formularios de eliminación y las variables de sesión de Laravel para disparar _Toasts_ y _Modals_ automáticamente.

## ⚙️ Guía Rápida de Instalación y Entorno Local

**Requisitos del Servidor:**

- PHP >= 8.2
- Composer
- Node.js y NPM
- MySQL o MariaDB (Ej. entorno XAMPP o Laragon)

**Paso a paso:**

1. **Obtener el código** (o abrir el directorio existente en tu servidor).
2. **Instalar el ecosistema de Backend:**
   `bash
composer install
`
3. **Instalar el ecosistema Frontend:**
   `bash
npm install
`
4. **Configurar las variables de entorno:**
   Copia el archivo .env.example y nómbralo .env. Ajusta tus credenciales:
   `env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=panaderia
DB_USERNAME=root
DB_PASSWORD=
`
5. **Autenticar y proteger la App:**
   `bash
php artisan key:generate
`
6. **Migrar la base de datos (y poblarla con el usuario Admin):**
   `bash
php artisan migrate --seed
`
7. **Empaquetar los Assets de Vite para Producción:**
   `bash
   npm run build
   `
   _(Si vas a modificar CSS/JS, puedes usar
   pm run dev en su lugar)._
8. **Encender servidor de aplicación Laravel:**
   `bash
php artisan serve
`
9. **¡Listo!** Entra desde tu navegador web a [http://localhost:8000](http://localhost:8000).

---

<div align="center">
    <i>Software desarrollado y ensamblado meticulosamente con los más  estándares de UI/UX corporativa Nady07. 🥖☕</i>
</div>
