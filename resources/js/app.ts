import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Ziggy } from './ziggy';
import { useTheme } from './composables/useTheme';

const appName = import.meta.env.VITE_APP_NAME || 'Escuela de Conducción';

// Inicializar apariencia global (tema base, modo, fuente, contraste)
const { initAppearance } = useTheme();
initAppearance({ autoDark: true }); // auto dark según hora del cliente si no hay preferencia guardada

createInertiaApp({
    title: (title: string) => `${title} - ${appName}`,
    resolve: (name: string) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }: any) {
        // Asegurar que las rutas inyectadas por @routes en window.Ziggy se mezclen
        // con la lista estática importada (evita errores cuando el archivo ziggy.js
        // fue generado antes de agregar nuevas rutas en el backend).
        if (typeof window !== 'undefined' && (window as any).Ziggy && (window as any).Ziggy.routes) {
            Object.assign(Ziggy.routes, (window as any).Ziggy.routes);
        }
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

