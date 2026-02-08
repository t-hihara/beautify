<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";

const { required = false, row = 7 } = defineProps<{
  modelValue: string | null;
  field: string;
  title?: string;
  required?: boolean;
  row?: number;
  placeholder?: string;
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: string | null];
}>();
</script>

<template>
  <div>
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div>
      <div class="relative">
        <textarea
          :rows="row"
          :value="modelValue"
          :placeholder="placeholder"
          :class="[error?.[field] ? 'border-red-600' : 'border-zinc-300', title ? 'mt-1' : '']"
          class="w-full px-3 py-2 bg-white rounded-lg shadow-sm border focus:outline-none focus:ring-rose-300 focus:border-rose-300"
          autocomplete="on"
          @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />
        <button
          v-if="modelValue"
          tabindex="-1"
          class="absolute top-2 right-2 flex items-center cursor-pointer transition ease-in-out duration-300 hover:text-rose-500"
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
