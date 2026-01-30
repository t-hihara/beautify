<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineOptions({
  layoutProps: { centerMain: true },
});
</script>

<template>
  <div class="w-full max-w-md mx-auto">
    <Head title="認証リンクの有効期限が切れています" />
    <div class="bg-zinc-100 rounded-lg shadow p-8 text-center space-y-6">
      <h2 class="text-2xl font-bold text-zinc-800">リンクの有効期限が切れています</h2>
      <p class="text-zinc-600 text-sm leading-relaxed">
        認証用リンクの有効期限が切れているか、無効なリンクです。<br />
        ログイン画面から「認証メールを再送信」をお試しください。
      </p>
      <div class="pt-4 space-y-2">
        <Link
          :href="route('user.loginForm')"
          class="block w-full py-2 text-sm text-zinc-600 underline hover:no-underline"
        >
          ログイン画面へ
        </Link>
      </div>
    </div>
  </div>
</template>
