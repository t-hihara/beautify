<script setup lang="ts">
import Logo from '@/assets/images/site-logo.png';

defineProps<{
  isAuth: boolean;
  user: any;
}>()
</script>

<template>
  <header class="fixed inset-0 bg-white">
    <div class="h-16 flex items-center justify-between border border-zinc-200">
      <div>
        <img :src="Logo" alt="" class="h-16">
      </div>
      <div>
        <div v-if="isAuth" class="">aaa</div>
        <div v-else class="p-3">ログイン</div>
      </div>
    </div>
  </header>
</template>
