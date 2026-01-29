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
    class="text-zinc-800 hover:text-zinc-500"
    @click="$emit('click')"
  >
    <slot />
  </button-base>
</template>
