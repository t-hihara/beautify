<script setup lang="ts">
import { computed, ref } from "vue";
import { ButtonPrimary } from "@/common/js/components/Ui/ButtonIndex";
import { XMarkIcon } from "@heroicons/vue/24/outline";

export type ImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

const {
  modelValue,
  title = "",
  required = false,
  error,
} = defineProps<{
  modelValue: File | string | null;
  field: string;
  title?: string;
  required?: boolean;
  error?: Record<string, string>;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: File | null];
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);
const previewUrl = computed(() => {
  if (modelValue === null) return undefined;
  if (modelValue instanceof File) return URL.createObjectURL(modelValue);
  return modelValue;
});

const openFileDialog = (): void => {
  fileInputRef.value?.click();
};

const onFileChange = (e: Event): void => {
  const file = (e.target as HTMLInputElement).files?.[0] ?? null;
  emit("update:modelValue", file);
  (e.target as HTMLInputElement).value = "";
};

const remove = (): void => {
  emit("update:modelValue", null);
};
</script>

<template>
  <div>
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex items-center gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div :class="title ? 'mt-1' : ''">
      <input ref="fileInputRef" type="file" class="hidden" @change="onFileChange" />
      <button-primary @click="openFileDialog">{{ previewUrl ? "画像を変更する" : "画像を追加する" }}</button-primary>
      <div v-if="previewUrl" class="mt-4">
        <div class="w-40 h-40 relative">
          <img :src="previewUrl" alt="" class="aspect-square object-center rounded-lg" />
          <button
            type="button"
            @click="remove()"
            class="absolute -top-2 -right-3 rounded-full border border-red-600 text-red-600 cursor-pointer"
          >
            <x-mark-icon class="size-4" />
          </button>
        </div>
      </div>
    </div>
    <div v-if="error?.[field]" class="mt-0.5">
      <span class="text-xs text-red-600">{{ error[field] }}</span>
    </div>
  </div>
</template>
