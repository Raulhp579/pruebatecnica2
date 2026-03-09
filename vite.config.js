import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/crearUsuario.js','resources/js/verUsuarios.js','resources/js/proyectos.js'],
            refresh: true,
        }),
    ],
});
