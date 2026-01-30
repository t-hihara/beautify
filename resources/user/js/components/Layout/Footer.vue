<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

const year = new Date().getFullYear();
</script>

<template>
  <footer class="mt-auto border-t border-zinc-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-6">
      <nav class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm text-zinc-600">
        <Link href="/" class="hover:text-zinc-800 hover:underline">利用規約</Link>
        <span class="text-zinc-400">|</span>
        <Link href="/" class="hover:text-zinc-800 hover:underline">プライバシーポリシー</Link>
        <span class="text-zinc-400">|</span>
        <Link href="/" class="hover:text-zinc-800 hover:underline">お問い合わせ</Link>
      </nav>
      <p class="mt-3 text-center text-sm text-zinc-500">© {{ year }}</p>
    </div>
  </footer>
</template>
