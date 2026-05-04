import "./bootstrap";
import "../css/app.css";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createPinia } from "pinia";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const pinia = createPinia();

createInertiaApp({
    // Format judul tab browser: 'Beranda - Toko Online'
    title: (title) => `${title} - Toko Online`,

    // Inertia mencari file Vue di folder resources/js/Pages/
    // Contoh: Inertia::render('Home') → mencari Pages/Home.vue
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin) // Aktifkan Inertia
            .use(pinia) // Aktifkan Pinia state management
            .use(ZiggyVue) // Aktifkan named routes di Vue
            .mount(el);
    },

    // Warna loading bar saat berpindah halaman
    progress: { color: "#2563EB" },
});
