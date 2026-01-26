<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";

type LoginForm = {
  email: string;
  password: string;
};

const form = useForm<LoginForm>({
  email: "",
  password: "",
});
</script>

<template>
  <Head title="ログイン" />
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-zinc-800">ログイン</h2>
      <div class="mt-8">
        <form>
          <div class="space-y-6">
            <div>
              <!-- form -->
              <div class="rounded-md shadow-sm -space-y-px">
                <div>
                  <label for="email" class="sr-only">メールアドレス</label>
                  <input
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    required
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
                    required
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-zinc-800 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    placeholder="メールアドレス"
                  />
                </div>
                <div></div>
              </div>
            </div>
            <div>
              <!-- button -->
              <button
                type="submit"
                :disabled="form.processing"
                class="group relative w-full flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                ログインする
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
