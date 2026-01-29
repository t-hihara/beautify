<script setup lang="ts">
import ButtonBase from "@common/components/Ui/ButtonBase.vue";

const {
  type = "button",
  href = null,
  isAnchor = false,
  disabled = false,
} = defineProps<{
  type?: "button" | "submit" | "reset";
  href?: string | null;
  isAnchor?: boolean;
  disabled?: boolean;
}>();

defineEmits<{
  click: [];
}>();
</script>

<template>
  <button-base
    :type="type"
    :href="href"
    :is-anchor="isAnchor"
    :disabled="disabled"
    :class="disabled ? 'bg-zinc-300 text-zinc-800' : 'bg-rose-500 hover:bg-rose-300 text-white hover:text-zinc-800'"
    class="px-3 py-2"
    @click="$emit('click')"
  >
    <slot />
  </button-base>
</template>
