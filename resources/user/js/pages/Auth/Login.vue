<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";

import Logo from "@/assets/images/site-logo.png";

type LoginForm = {
  email: string;
  password: string;
};

const form = useForm<LoginForm>({
  email: "",
  password: "",
});

const submit = (): void => {
  form.post(route("user.login"));
};
</script>

<template>
  <Head title="ログイン" />
  <div class="min-h-[calc(100vh-4rem)] flex justify-center items-center">
    <div class="max-w-md w-full bg-zinc-200 rounded-md shadow-xl p-8">
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl font-bold text-zinc-800">ログイン</h2>
        </div>
        <form @submit.prevent="submit" class="space-y-5">
          <div class="flex flex-col gap-2">
            <label for="email">メールアドレス</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="example@email.com"
              class="px-3 py-2 rounded-md bg-white border border-zinc-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500"
            />
          </div>
          <div class="flex flex-col gap-2">
            <label for="password">パスワード</label>
            <input
              v-model="form.password"
              type="password"
              placeholder="password"
              class="px-3 py-2 rounded-md bg-white border border-zinc-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500"
            />
          </div>
        </form>

        <div class="mt-6">
          <div class="text-center">
            <h2 class="text-2xl font-bold text-zinc-800">他サイトIDでログイン</h2>
          </div>
          <div class="mt-4 w-2/3 mx-auto">
            <button
              class="w-full bg-white flex items-center justify-center px-4 py-3 border border-zinc-300 rounded-full text-sm text-semibold text-zinc-800 hover:bg-zinc-50 transition ease-in-out duration-300"
            >
              Googleでログイン
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
