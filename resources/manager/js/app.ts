/// <reference types='vite/client' />

import "./bootstrap";
import "@common/@types/inertia.d.ts";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { DefineComponent } from "vue";
import { ZiggyVue } from "ziggy-js";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

import IndexLayout from "./layouts/Index.vue";

createInertiaApp({
  resolve: async (name) => {
    const page = await resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob<DefineComponent>("./pages/**/*.vue"),
    );
    page.default.layout = IndexLayout;
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(LaravelPermissionToVueJS)
      .use(ZiggyVue)
      .mount(el);
  },
});
