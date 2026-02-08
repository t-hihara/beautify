<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed, type Component } from "vue";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import { useUnloadedExportFileCount } from "@manager/composables/useUnloadedExportFileCount";
import { adminMenu, shopMenu } from "@manager/config/SidebarMenu";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import {
  BuildingStorefrontIcon,
  HomeIcon,
  DocumentDuplicateIcon,
  FolderIcon,
  ChevronDownIcon,
} from "@heroicons/vue/24/outline";

const { count: unloadedExportFileCount } = useUnloadedExportFileCount();
const guard = useGuard();
const iconMap: Record<string, Component> = {
  dashboard: HomeIcon,
  shop: BuildingStorefrontIcon,
  export: FolderIcon,
  log: DocumentDuplicateIcon,
};
const menus = computed(() => (guard.value === "admin" ? adminMenu : shopMenu));
</script>

<template>
  <aside class="min-h-screen bg-rose-400 text-white">
    <div class="p-4 h-16 flex justify-start items-center border-b border-zinc-300/50">
      <h1 class="text-xl">Beautify</h1>
    </div>
    <nav class="mt-4 p-4">
      <ul class="space-y-2">
        <li v-for="menu in menus" :key="menu.id">
          <disclosure v-if="menu.children.length > 0" v-slot="{ open }">
            <disclosure-button
              class="w-full px-3 py-2 rounded-md hover:bg-zinc-300/60 transition ease-in-out duration-300 flex items-center justify-between cursor-pointer"
            >
              <span class="flex items-center gap-2">
                <component :is="iconMap[menu.icon]" class="size-5" />
                <span class="">{{ menu.label }}</span>
              </span>
              <chevron-down-icon class="size-5 transition ease-in-out duration-300" :class="{ 'rotate-180': open }" />
            </disclosure-button>
            <transition enter-active-class="animate-fade-in" leave-active-class="animate-fade-out">
              <disclosure-panel class="mt-1 ml-10 py-1 space-y-4 flex flex-col">
                <Link v-for="child in menu.children" :key="child.id" :href="route(child.route)">{{ child.label }}</Link>
              </disclosure-panel>
            </transition>
          </disclosure>
          <Link
            v-else
            :href="route(menu.route ?? '')"
            class="flex w-full items-center justify-between px-3 py-2 rounded-md hover:bg-zinc-300/60 transition ease-in-out duration-300"
          >
            <div class="flex items-center gap-2">
              <component :is="iconMap[menu.icon]" class="size-5" />
              <span>{{ menu.label }}</span>
            </div>
            <div
              v-if="menu.icon === 'export' && unloadedExportFileCount > 0"
              class="min-w-5 min-h-5 p-1 text-xs font-semibold leading-none px-1.5 py-0.5 rounded-full bg-white/90 text-rose-600 flex items-center justify-center"
            >
              {{ unloadedExportFileCount }}
            </div>
          </Link>
        </li>
      </ul>
    </nav>
  </aside>
</template>
