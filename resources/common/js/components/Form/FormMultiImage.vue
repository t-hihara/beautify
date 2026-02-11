<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";
import { ButtonPrimary } from "@/common/js/components/Ui/ButtonIndex";

export type ImageType = {
  id: number;
  filename: string;
  filePath: string;
};

export type MultiImageFormValue = {
  keepImageIds: number[];
  newImages: File[];
};

const {
  modelValue,
  field,
  title = "",
  required = false,
  existingImages = [],
  maxCount,
  error,
} = defineProps<{
  modelValue: MultiImageFormValue;
  field: string;
  title?: string;
  required?: boolean;
  existingImages?: ImageType[];
  maxCount?: number;
  error?: Record<string, string>;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: MultiImageFormValue];
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);

const keptExisting = computed<ImageType[]>(() =>
  (existingImages ?? []).filter((image) => modelValue.keepImageIds.includes(image.id)),
);

const newImagePreviewUrls = computed<string[]>(() => modelValue.newImages.map((file) => URL.createObjectURL(file)));

const totalImageCount = computed<number>(() => modelValue.keepImageIds.length + modelValue.newImages.length);

const canAddMoreImage = computed<boolean>(() => (maxCount === undefined ? true : totalImageCount.value < maxCount));

const newImageErrors = computed<string[]>(() =>
  modelValue.newImages.map((_, index) => error?.[`${field}.newImages.${index}`] ?? ""),
);

const existingImageErrors = computed<string[]>(() => {
  if (!error) return [];
  return Object.entries(error)
    .filter(([key]) => key === `${field}.keepImageIds` || key.startsWith(`${field}.keepImageIds.`))
    .map(([, msg]) => msg as string);
});

const openFileDialog = (): void => {
  fileInputRef.value?.click();
};

const addFile = (e: Event): void => {
  const files = Array.from((e.target as HTMLInputElement).files || []);
  const rest = maxCount !== undefined ? maxCount - totalImageCount.value : files.length;
  if (rest <= 0) return;

  emit("update:modelValue", {
    keepImageIds: modelValue.keepImageIds,
    newImages: [...modelValue.newImages, ...files.slice(0, rest)],
  });
};

const removeExistingImage = (id: number): void => {
  emit("update:modelValue", {
    keepImageIds: modelValue.keepImageIds.filter((x) => x !== id),
    newImages: modelValue.newImages,
  });
};

const removeNewImage = (index: number): void => {
  emit("update:modelValue", {
    keepImageIds: modelValue.keepImageIds,
    newImages: modelValue.newImages.filter((_, i) => i !== index),
  });
};
</script>

<template>
  <div>
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex items-center gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <div :class="title ? 'mt-1' : ''">
      <div class="flex items-center gap-2">
        <input ref="fileInputRef" type="file" multiple class="hidden" @change="addFile" />
        <button-primary @click="openFileDialog" :disabled="!canAddMoreImage">画像を追加する</button-primary>
        <span class="text-xs text-red-600">{{
          canAddMoreImage ? "追加できる画像は5枚までです。" : "追加できる上限に達しています。"
        }}</span>
      </div>
      <div v-if="existingImageErrors.length > 0" class="mt-1">
        <span v-for="(msg, i) in existingImageErrors" :key="i" class="text-xs text-red-600">{{ msg }}</span>
      </div>
      <div v-if="keptExisting.length > 0 || modelValue.newImages.length > 0" class="mt-2 grid grid-cols-5 gap-4">
        <template v-if="keptExisting.length > 0">
          <div v-for="image in keptExisting" :key="image.id" class="relative col-span-1 rounded-lg">
            <img :src="image.filePath" :alt="image.filename" class="aspect-square w-full object-cover rounded-lg" />
            <button
              type="button"
              @click="removeExistingImage(image.id)"
              class="absolute -top-2 -right-3 rounded-full border border-red-600 text-red-600 cursor-pointer"
            >
              <x-mark-icon class="size-4" />
            </button></div
        ></template>
        <template v-if="modelValue.newImages.length > 0">
          <div v-for="(url, index) in newImagePreviewUrls" :key="index" class="relative col-span-1 rounded-lg">
            <img :src="url" alt="" class="aspect-square w-full object-cover rounded-lg" />
            <div v-if="newImageErrors[index]" class="mt-0.5">
              <span class="text-xs text-red-600">{{ newImageErrors[index] }}</span>
            </div>
            <button
              type="button"
              @click="removeNewImage(index)"
              class="absolute -top-2 -right-2 rounded-full border border-red-600 text-red-600 cursor-pointer"
            >
              <x-mark-icon class="size-4" />
            </button>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
