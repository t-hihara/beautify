<script setup lang="ts">
import ButtonBase from "@common/components/Ui/ButtonBase.vue";
import { computed } from "vue";

const {
  href = null,
  isAnchor = true,
  variant = "default",
  disabled = false,
} = defineProps<{
  href?: string | null;
  isAnchor?: boolean;
  variant?: "default" | "outline" | "filled";
  disabled?: boolean;
}>();

const styleClass = computed(() => {
  if (disabled) return "bg-zinc-300 text-zinc-800";
  if (variant === "outline") {
    return "border border-zinc-300 bg-transparent text-zinc-800 hover:bg-zinc-100";
  }
  if (variant === "filled") {
    return "bg-white text-zinc-800 hover:bg-zinc-100";
  }
  return "text-zinc-800 underline hover:no-underline hover:text-rose-600";
});

defineEmits<{
  click: [];
}>();
</script>

<template>
  <button-base
    :href="href"
    :is-anchor="isAnchor"
    :disabled="disabled"
    :class="styleClass"
    class="px-3 py-2"
    @click="$emit('click')"
  >
    <slot />
  </button-base>
</template>
