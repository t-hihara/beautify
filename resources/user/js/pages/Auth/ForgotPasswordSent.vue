<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineOptions({
  layoutProps: { centerMain: true },
});
</script>

<template>
  <div class="w-full max-w-md mx-auto">
    <Head title="パスワード再設定用のメールを送信しました" />
    <div class="bg-zinc-100 rounded-lg shadow p-8 text-center space-y-6">
      <h2 class="text-2xl font-bold text-zinc-800">送信しました</h2>
      <p class="text-zinc-600 text-sm leading-relaxed">
        ご入力いただいたメールアドレスに、パスワード再設定用のリンクを送信しました。<br />
        メール内のリンクをクリックして、新しいパスワードを設定してください。
      </p>
      <p class="text-zinc-500 text-xs">
        メールが届かない場合は、迷惑メールフォルダをご確認ください。
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
