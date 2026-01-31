<script setup lang="ts">
const {
  required = false,
  isFlex = false,
  labelPosition = "before",
} = defineProps<{
  modelValue: boolean;
  field: string;
  title?: string;
  required?: boolean;
  isFlex?: boolean;
  labelPosition?: "before" | "after";
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: boolean];
}>();
</script>

<template>
  <div :class="isFlex ? 'flex items-center gap-2' : ''">
    <label
      v-if="title"
      :for="field"
      :class="labelPosition === 'after' ? 'order-2' : 'order-1'"
      class="text-sm font-medium text-zinc-800 flex items-center gap-1"
    >
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div
      :class="[
        title && !isFlex ? 'mt-1' : '',
        isFlex ? 'flex items-center' : '',
        isFlex && labelPosition === 'after' ? 'order-1' : 'order-2',
      ]"
    >
      <input
        type="checkbox"
        :id="field"
        :field="field"
        :checked="modelValue"
        class="size-4 shrink-0 focus:outline-none focus:ring-rose-500 focus:ring-offset-0"
        @change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
      />
      <div v-if="error?.[field]" class="mt-0.5">
        <span class="text-xs text-red-600">{{ error[field] }}</span>
      </div>
    </div>
  </div>
</template>
