<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineOptions({
  layoutProps: { centerMain: true },
});
</script>

<template>
  <div class="w-full max-w-md mx-auto">
    <Head title="メールアドレスの認証が完了しました" />
    <div class="bg-zinc-100 rounded-lg shadow p-8 text-center space-y-6">
      <h2 class="text-2xl font-bold text-zinc-800">認証が完了しました</h2>
      <p class="text-zinc-600 text-sm leading-relaxed">
        メールアドレスの認証が完了しました。<br />
        ご利用いただくことができます。
      </p>
      <div class="pt-4">
        <Link
          :href="route('home.index')"
          class="inline-block px-6 py-2 rounded-lg bg-rose-400 hover:bg-rose-300 text-white text-sm font-medium transition"
        >
          トップへ
        </Link>
      </div>
    </div>
  </div>
</template>
