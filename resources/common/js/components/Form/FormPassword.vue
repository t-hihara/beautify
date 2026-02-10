<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";

const { required = false } = defineProps<{
  modelValue: string;
  field: string;
  title?: string;
  required?: boolean;
  placeholder?: string;
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: string];
}>();
</script>

<template>
  <div>
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex items-center gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div>
      <div class="relative">
        <input
          type="password"
          :value="modelValue"
          :placeholder="placeholder"
          :class="[error?.[field] ? 'border-red-600' : 'border-zinc-300', title ? 'mt-1' : '']"
          class="w-full h-10 pl-3 pr-10 py-2 bg-white rounded-lg shadow-sm border focus:outline-none focus:ring-rose-300 focus:border-rose-300"
          @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />
        <button
          v-if="modelValue"
          tabindex="-1"
          class="absolute top-1/2 right-2 -translate-1/2 flex items-center cursor-pointer transition ease-in-out duration-300 hover:text-rose-500"
          @click="$emit('update:modelValue', '')"
        >
          <x-mark-icon class="size-4 mt-1" />
        </button>
      </div>
      <div v-if="error?.[field]" class="mt-0.5">
        <span class="text-xs text-red-600">{{ error[field] }}</span>
      </div>
    </div>
  </div>
</template>
