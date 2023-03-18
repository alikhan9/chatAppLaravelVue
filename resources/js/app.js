import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import AuthenticatedLayoutVue from "./Layouts/AuthenticatedLayout.vue";
import Unicon from "vue-unicons";
import {
    uniLayerGroupMonochrome,
    uniCarWash,
    uniEllipsisV,
    uniBell,
} from "vue-unicons/dist/icons";

Unicon.add([uniLayerGroupMonochrome, uniCarWash, uniEllipsisV, uniBell]);

createInertiaApp({
    title: (title) => `ChatApp - ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || AuthenticatedLayoutVue;
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Unicon)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
