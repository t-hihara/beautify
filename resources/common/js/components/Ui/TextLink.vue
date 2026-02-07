<script setup lang="ts">
import ButtonBase from "@common/components/Ui/ButtonBase.vue";

const { isAnchor = false, disabled = false } = defineProps<{
  href: string;
  isAnchor?: boolean;
  disabled?: boolean;
}>();
</script>

<template>
  <button-base :href="href" :is-anchor="isAnchor" :disabled="disabled" class="text-zinc-800 underline"
    ><slot
  /></button-base>
</template>
