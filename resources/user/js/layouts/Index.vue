<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import type { User } from "@common/@types/inertia";

import Footer from "@user/components/Layout/Footer.vue";
import Header from "@user/components/Layout/Header.vue";

const page = usePage();
const user = computed<User | null>(() => page.props.auth.user);
</script>

<template>
  <div class="flex min-h-screen flex-col">
    <Header :user="user" />
    <main class="flex-1">
      <slot></slot>
    </main>
    <Footer />
  </div>
</template>
