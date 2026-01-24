<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>

<template>

  <Head title="トップページ" />
  <div>
    <div class="p-4">
      <Link :href="route('shops.index')">店舗一覧へ遷移</Link>
    </div>
  </div>
</template>
