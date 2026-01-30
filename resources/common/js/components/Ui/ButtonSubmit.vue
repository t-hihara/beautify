<script setup lang="ts">
import ButtonBase from "@common/components/Ui/ButtonBase.vue";

const { disabled = false } = defineProps<{
  disabled?: boolean;
}>();

defineEmits<{
  click: [];
}>();
</script>

<template>
  <button-base
    type="submit"
    :href="null"
    :is-anchor="false"
    :disabled="disabled"
    :class="disabled ? 'bg-zinc-300 text-zinc-800' : 'bg-rose-400 hover:bg-rose-300 text-white hover:text-zinc-800'"
    class="px-3 py-2 rounded-lg"
    @click="$emit('click')"
  >
    <slot />
  </button-base>
</template>
