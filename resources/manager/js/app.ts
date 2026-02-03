/// <reference types='vite/client' />

import "./bootstrap";
import "@common/@types/inertia.d.ts";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { DefineComponent } from "vue";
import { ZiggyVue } from "ziggy-js";
import { VueDatePicker } from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import IndexLayout from "./layouts/Index.vue";
import GuestLayout from "./layouts/Guest.vue";

createInertiaApp({
  resolve: async (name) => {
    const page = await resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob<DefineComponent>("./pages/**/*.vue"),
    );
    if (name.startsWith("Auth/")) {
      page.default.layout = GuestLayout;
    } else {
      page.default.layout = IndexLayout;
    }
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(LaravelPermissionToVueJS)
      .use(ZiggyVue)
      .use(Toast, {
        timeout: 3000,
        position: "top-right",
        closeOnClick: true,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
      })
      .component("VueDatePicker", VueDatePicker)
      .mount(el);
  },
});
