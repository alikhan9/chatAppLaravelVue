import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
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
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
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
