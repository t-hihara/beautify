<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { useGuard } from "../composables/useGuard";
import type { User } from "@common/@types/inertia";

import Header from "@manager/components/Layout/Header.vue";

const { guard } = useGuard();
const page = usePage();
const isAdmin = computed<boolean>(() => guard.value === "admin");
const user = computed<User | null>(() => page.props.auth.user || null);
</script>

<template>
  <div>
    <Header :is-admin="isAdmin" :user="user" />
    <main>
      <slot></slot>
    </main>
  </div>
</template>
