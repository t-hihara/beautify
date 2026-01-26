<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import type { User } from "@common/@types/inertia";

import Header from "@manager/components/Layout/Header.vue";

const page = usePage();
const user = computed<User | null>(() => page.props.auth.user || null);
</script>

<template>
  <div>
    <Header :user="user" />
    <main>
      <slot></slot>
    </main>
  </div>
</template>
