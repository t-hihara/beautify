<script setup lang="ts">
import type { User } from "@common/@types/inertia";
import { route } from "ziggy-js";

const { isAdmin } = defineProps<{
  isAdmin: boolean;
  user: User | null;
}>();

const submit = (): void => {
  isAdmin ? route("admin.logout") : route("shop.logout");
};
</script>

<template>
  <header class="bg-white shadow-sm h-16">
    <div class="flex justify-end items-center">
      <template v-if="user">
        <div class="flex items-center gap-4">
          <span>{{ user.name }}</span>
          <form @submit.prevent="submit">
            <button type="submit" class="px-4 py-2 rounded-md bg-orange-400">ログアウト</button>
          </form>
        </div>
      </template>
    </div>
  </header>
</template>

<style scoped></style>
