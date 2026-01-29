<script setup lang="ts">
import Logo from "@/assets/images/site-logo.png";
import { Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import type { User } from "@common/@types/inertia";
import { ButtonPrimary } from "@/common/js/components/Ui/ButtonIndex";

defineProps<{
  user: User | null;
}>();

const submit = (): void => {
  router.post(route("user.logout"));
};
</script>

<template>
  <header class="sticky inset-0 bg-white">
    <div class="h-16 flex items-center justify-between border border-zinc-200">
      <div>
        <Link href="/">
          <img :src="Logo" alt="" class="h-16" />
        </Link>
      </div>
      <div class="px-4">
        <div v-if="user" class="flex items-center gap-2">
          <span>{{ user.name }}</span>
          <button
            type="button"
            @click="submit"
            class="px-3 py-2 rounded-md bg-orange-500 hover:bg-orange-300 transition ease-in-out duration-300"
          >
            ログアウト
          </button>
        </div>
        <div v-else class="p-3">
          <button-primary :href="route('user.login')">ログイン</button-primary>
        </div>
      </div>
    </div>
  </header>
</template>
