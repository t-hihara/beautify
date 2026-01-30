<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps<{
  href: string;
}>();
</script>

<template>
  <Link :href="href" class="text-zinc-800 underline"><slot /></Link>
</template>
