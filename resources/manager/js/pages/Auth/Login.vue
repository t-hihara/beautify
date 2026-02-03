<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { useGuard } from "@manager/composables/useGuard";
import { computed } from "vue";
import { route } from "ziggy-js";

import { ButtonSubmit } from "@common/components/Ui/ButtonIndex";

type LoginForm = {
  email: string;
  password: string;
};

const { guard } = useGuard();
const isAdmin = computed((): boolean => guard.value === "admin");

const form = useForm<LoginForm>({
  email: "",
  password: "",
});

const submit = (): void => form.post(route(`${guard.value}.login`));
</script>

<template>
  <Head :title="isAdmin ? '管理者ログイン' : '店舗ログイン'" />
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-zinc-800">
        {{ isAdmin ? "管理者ログイン" : "店舗ログイン" }}
      </h2>
      <div class="mt-8">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <div>
              <div class="rounded-md shadow-sm -space-y-px">
                <div>
                  <label for="email" class="sr-only">メールアドレス</label>
                  <input
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-zinc-800 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    placeholder="メールアドレス"
                  />
                </div>
                <div>
                  <label for="password" class="sr-only">パスワード</label>
                  <input
                    v-model="form.password"
                    type="password"
                    name="password"
                    autocomplete="password"
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-zinc-800 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    placeholder="password"
                  />
                </div>
              </div>
              <div v-if="form.errors.email" class="mt-1">
                <span class="text-sm text-red-500">{{ form.errors.email }}</span>
              </div>
              <div v-if="form.errors.password" class="mt-1">
                <span class="text-sm text-red-500">{{ form.errors.password }}</span>
              </div>
            </div>
            <div class="flex justify-center">
              <button-submit>ログインする</button-submit>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
