<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import type { User } from "@common/@types/inertia";

import Footer from "@user/components/Layout/Footer.vue";
import Header from "@user/components/Layout/Header.vue";
import FlashMessage from "@common/components/Layout/FlashMessage.vue";

defineProps<{
  centerMain?: boolean;
}>();

const page = usePage();
const user = computed<User | null>(() => page.props.auth.user);
</script>

<template>
  <flash-message />
  <div class="flex min-h-screen flex-col">
    <Header :user="user" />
    <main class="flex min-h-0 flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <div class="flex min-h-full min-w-0 flex-1 flex-col" :class="{ 'sm:justify-center': centerMain }">
        <slot />
      </div>
    </main>
    <Footer />
  </div>
</template>
