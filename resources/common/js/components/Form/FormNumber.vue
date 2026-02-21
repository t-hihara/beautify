<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";

const {
  modelValue,
  field,
  title,
  required = false,
  placeholder,
  error,
  min,
} = defineProps<{
  modelValue: number | null;
  field: string;
  title?: string;
  required?: boolean;
  placeholder?: string;
  error?: Record<string, string>;
  /** 最小値。指定時はこの値未満をこの値に補正（例: 0 でマイナス→0） */
  min?: number;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: number | null];
}>();

const displayValue = (): number | string => {
  const v = modelValue;
  return v != null && !Number.isNaN(v) ? v : "";
};

const onInput = (e: Event): void => {
  const num = Number((e.target as HTMLInputElement).value);
  if (Number.isNaN(num)) {
    emit("update:modelValue", null);
    return;
  }
  const value = min !== undefined ? Math.max(min, num) : num;
  emit("update:modelValue", value);
};
</script>

<template>
  <div>
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex items-center gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div>
      <div class="relative">
        <input
          type="text"
          :value="displayValue()"
          :placeholder="placeholder"
          :class="[error?.[field] ? 'border-red-600' : 'border-zinc-300', title ? 'mt-1' : '']"
          class="w-full h-10 pl-3 pr-10 py-2 bg-white rounded-lg shadow-sm border focus:outline-none focus:ring-rose-300 focus:border-rose-300"
          @input="onInput"
        />
        <button
          v-if="modelValue != null && !Number.isNaN(modelValue)"
          type="button"
          tabindex="-1"
          class="absolute top-1/2 right-2 -translate-y-1/2 flex items-center cursor-pointer transition ease-in-out duration-300 hover:text-rose-500"
          @click="emit('update:modelValue', min !== undefined ? min : 0)"
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
