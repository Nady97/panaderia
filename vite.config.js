import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'


export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            // Agrega aquí todos los archivos CSS y JS que quieras compilar
            input: [
                'resources/css/global.css',
                'resources/css/components.css',
                'resources/css/sidebar.css',
                'resources/css/login.css',
                'resources/js/app.js',
                'resources/js/perfil.js',
                'resources/js/usuarios.js',
                'resources/js/dashboard.js',
                'resources/js/productos.js',
                'resources/js/categorias.js',

            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});




