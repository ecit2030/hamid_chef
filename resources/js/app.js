import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/index.esm.js';
import AppRoot from './App.vue'
import '../css/app.css';
import './bootstrap';
import './assets/main.css'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'flatpickr/dist/flatpickr.css'
import VueApexCharts from 'vue3-apexcharts'
import { i18n, setHtmlDirection } from './i18n'
import { useGlobalLoading } from './composables/useGlobalLoading'

const appName = import.meta.env.VITE_APP_NAME || 'Mon Chef';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        // Ensure direction (RTL/LTR) and language are applied on startup
        setHtmlDirection(i18n.global.locale.value)

        // Hook global Inertia router events to toggle loading overlay
        const {showGlobalLoading, hideGlobalLoading} = useGlobalLoading()
        router.on('start', () => showGlobalLoading())
        router.on('finish', () => hideGlobalLoading())
        router.on('error', () => hideGlobalLoading())
        router.on('cancel', () => hideGlobalLoading())
        createApp({
            render: () =>
                h(AppRoot, null, {
                    default: () => h(App, props),
                }),
        })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
             .use(VueApexCharts)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
}).then(r => {})
