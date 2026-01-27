<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { useGuard } from "@manager/composables/useGuard";
import { adminMenu, shopMenu } from "@manager/config/SidebarMenu";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { BuildingStorefrontIcon, HomeIcon } from "@heroicons/vue/24/outline";
import { computed, type Component } from "vue";
import { route } from "ziggy-js";

const { guard } = useGuard();
const IconMap: Record<string, Component> = {
  dashboard: HomeIcon,
};
const menus = computed(() => (guard.value === "admin" ? adminMenu : shopMenu));
</script>

<template>
  <aside class="min-h-screen bg-orange-700 text-white">
    <div class="p-4 h-16 flex justify-start items-center border-b border-zinc-300/50">
      <h1 class="text-xl">Beautify</h1>
    </div>
    <nav class="mt-4 p-4">
      <ul class="space-y-2">
        <li v-for="menu in menus" :key="menu.id">
          <disclosure v-if="menu.children.length > 0" v-slot="{ open }"> </disclosure>
          <Link
            v-else
            :href="route(menu.route)"
            class="block px-3 py-2 rounded-md hover:bg-zinc-400/60 transition ease-in-out duration-300"
          >
            {{ menu.label }}
          </Link>
        </li>
      </ul>
    </nav>
  </aside>
</template>
