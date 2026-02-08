<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted } from "vue";
import { useGuard } from "@manager/composables/useGuard";
import { useUnloadedExportFileCount } from "@manager/composables/useUnloadedExportFileCount";
import type { User } from "@common/@types/inertia";

import Header from "@manager/components/Layout/Header.vue";
import Sidebar from "@manager/components/Layout/Sidebar.vue";
import FlashMessage from "@common/components/Layout/FlashMessage.vue";

const page = usePage();
const guard = useGuard();
const isAdmin = computed<boolean>(() => guard.value === "admin");
const user = computed<User | null>(() => page.props.auth.user || null);

const { refresh } = useUnloadedExportFileCount();

onMounted(() => {
  const onVisible = (): void => {
    if (document.visibilityState === "visible") refresh();
  };

  document.addEventListener("visibilitychange", onVisible);

  onUnmounted(() => {
    document.removeEventListener("visibilitychange", onVisible);
  });
});
</script>

<template>
  <flash-message />
  <div class="min-h-screen bg-zinc-100 grid grid-cols-12">
    <Sidebar class="col-span-2" />
    <div class="col-span-10">
      <Header :is-admin="isAdmin" :user="user" />
      <main class="px-8 py-12">
        <slot></slot>
      </main>
    </div>
  </div>
</template>
