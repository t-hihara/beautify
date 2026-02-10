<script setup lang="ts">
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

const { required = false } = defineProps<{
  modelValue: boolean;
  field: string;
  title?: string;
  required?: boolean;
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: boolean];
}>();
</script>

<template>
  <div>
    <SwitchGroup>
      <SwitchLabel v-if="title" class="text-sm font-medium text-zinc-800 flex items-center gap-1"
        >{{ title }}<span v-if="required" class="text-red-500">※</span></SwitchLabel
      >
      <div :class="title ? 'mt-1 h-10 flex items-center' : ''">
        <Switch
          :model-value="modelValue"
          :class="modelValue ? 'bg-rose-400' : 'bg-zinc-200'"
          class="relative inline-flex h-7 w-13 shrink-0 cursor-pointer items-center rounded-full border border-transparent transition-colors ease-in-out duration-300 focus:outline-none"
          @update:model-value="$emit('update:modelValue', $event)"
        >
          <span class="sr-only">{{ title ?? field }}</span>
          <span
            :class="modelValue ? 'translate-x-6' : 'translate-x-0.5'"
            class="inline-block size-6 transform rounded-full bg-white shadow transition ease-in-out duration-300"
            aria-hidden="true"
        /></Switch></div
    ></SwitchGroup>
  </div>
</template>
