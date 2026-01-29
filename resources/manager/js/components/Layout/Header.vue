<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import type { User } from "@common/@types/inertia";

import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";

const { isAdmin } = defineProps<{
  isAdmin: boolean;
  user: User | null;
}>();

const submit = (): void => {
  isAdmin ? router.post(route("admin.logout")) : router.post(route("shop.logout"));
};
</script>

<template>
  <header class="px-4 bg-white border border-zinc-200 shadow-sm h-16 flex justify-end items-center">
    <template v-if="user">
      <div class="flex items-center gap-4">
        <span>{{ user.name }}</span>
        <form @submit.prevent="submit">
          <button-submit>ログアウト</button-submit>
        </form>
      </div>
    </template>
  </header>
</template>

<style scoped></style>
