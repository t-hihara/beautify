<script setup lang="ts">
import { computed } from "vue";
import { Listbox, ListboxButton, ListboxOptions, ListboxOption, ListboxLabel } from "@headlessui/vue";
import { ChevronDownIcon, CheckIcon } from "@heroicons/vue/24/outline";
import type { EnumType } from "@common/lib";

const {
  modelValue,
  items,
  required = false,
  showClear = false,
} = defineProps<{
  modelValue: (string | number)[];
  field: string;
  title?: string;
  required?: boolean;
  showClear?: boolean;
  items: EnumType[];
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: (string | number)[]];
}>();

const selectedItems = computed(() => items.filter((item) => modelValue.includes(item.id)));
</script>

<template>
  <div>
    <listbox
      :model-value="selectedItems"
      multiple
      @update:model-value="
        (selectedArray: EnumType[]) =>
          $emit(
            'update:modelValue',
            selectedArray.map((item: EnumType) => item.id),
          )
      "
    >
      <listbox-label v-if="title" class="block text-sm font-medium text-zinc-800">
        {{ title }}<span v-if="required" class="text-red-500">※</span>
      </listbox-label>
      <div class="w-full relative">
        <listbox-button
          v-slot="{ open }"
          :class="[error?.[field] ? 'border-red-600' : 'border-zinc-300', title ? 'mt-1' : '']"
          class="w-full h-10 px-3 py-2 bg-white rounded-lg shadow-sm border cursor-pointer flex items-center justify-between focus:outline-none focus:ring-rose-300 focus:border-rose-300"
        >
          <span class="line-clamp-1 min-w-0 flex-1 text-left">{{
            selectedItems.length > 0 ? selectedItems.map((item) => item.name).join(", ") : "すべて"
          }}</span>
          <div class="flex items-center gap-2">
            <button
              v-if="showClear"
              type="button"
              class="px-2 py-1 rounded-lg border border-zinc-300 hover:bg-zinc-200 transition ease-in-out duration-300 text-xs cursor-pointer"
              @click="$emit('update:modelValue', [])"
            >
              クリア
            </button>
            <chevron-down-icon class="size-4 transition-transform duration-200" :class="{ 'rotate-180': open }" />
          </div>
        </listbox-button>
        <transition enter-active-class="animate-fade-in" leave-active-class="animate-fade-out">
          <listbox-options
            class="absolute z-10 w-full mt-1 bg-white rounded-lg border border-zinc-300 shadow-lg max-h-60 overflow-scroll"
          >
            <listbox-option
              v-for="item in items"
              :key="item.id"
              :value="item"
              class="px-3 py-2 hover:bg-zinc-100 cursor-pointer"
            >
              <div class="flex justify-between items-center">
                <span>{{ item.name }}</span>
                <span v-if="selectedItems.some((s) => s.id === item.id)"><check-icon class="size-4" /></span>
              </div>
            </listbox-option>
          </listbox-options>
        </transition>
      </div>
    </listbox>
    <div v-if="error?.[field]" class="mt-0.5">
      <span class="text-xs text-red-600">{{ error[field] }}</span>
    </div>
  </div>
</template>
