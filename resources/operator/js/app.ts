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
import axios from "axios";

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
    const permissions = props.initialPage.props.permissions;
    if (permissions) {
      (window as any).Laravel = (window as any).Laravel || {};
      (window as any).Laravel.jsPermissions = typeof permissions === "string" ? JSON.parse(permissions) : permissions;
    }
    axios.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          window.dispatchEvent(new CustomEvent("auth-expired"));
        }
        return Promise.reject(error);
      },
    );
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
