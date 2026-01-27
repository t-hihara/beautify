<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import type { User } from "@common/@types/inertia";

const { isAdmin } = defineProps<{
  isAdmin: boolean;
  user: User | null;
}>();

const submit = (): void => {
  isAdmin ? router.post(route("admin.logout")) : router.post(route("shop.logout"));
};
</script>

<template>
  <header class="bg-white border border-zinc-200 shadow-sm h-16">
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
