<script setup lang="ts">
import ButtonBase from "@common/components/Ui/ButtonBase.vue";

const {
  type = "button",
  href = null,
  isAnchor = false,
  disabled = false,
} = defineProps<{
  type?: "button" | "submit";
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
    :class="disabled ? 'text-zinc-800' : 'text-red-600 hover:text-red-300'"
    class="p-2"
    @click="$emit('click')"
    ><slot
  /></button-base>
</template>

<style scoped></style>
