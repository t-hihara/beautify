<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { type Component, computed } from "vue";

type ComponentType = "button" | "a" | Component;

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

const component = computed<ComponentType>(() => {
  if (!href) return "button";
  return isAnchor ? "a" : Link;
});

defineEmits<{
  click: [];
}>();
</script>

<template>
  <component
    :is="component"
    :type="href ? undefined : type"
    :href="href ?? undefined"
    :disabled="!href ? disabled : undefined"
    :class="disabled ? '' : 'cursor-pointer'"
    class="block transition ease-in-out duration-300 overflow-hidden"
    @click="!disabled && $emit('click')"
  >
    <slot />
  </component>
</template>
