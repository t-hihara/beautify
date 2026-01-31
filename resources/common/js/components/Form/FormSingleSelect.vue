<script setup lang="ts">
import { Listbox, ListboxButton, ListboxOptions, ListboxOption, ListboxLabel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/24/outline";
import type { Enum } from "@common/lib";
import { computed } from "vue";

const {
  modelValue,
  items,
  required = false,
} = defineProps<{
  modelValue: string | number;
  field: string;
  title?: string;
  required?: boolean;
  items: Enum[];
  error?: Record<string, string>;
}>();

defineEmits<{
  "update:modelValue": [value: string | number];
}>();

const selectedItem = computed(() => items.find((item) => item.id === modelValue));
</script>

<template>
  <div class="relative">
    <listbox :model-value="selectedItem" @update:model-value="(item) => $emit('update:modelValue', item.id)">
      <listbox-label v-if="title" class="block text-sm font-medium text-zinc-800">
        {{ title }}<span v-if="required" class="text-red-500">※</span>
      </listbox-label>
      <listbox-button
        v-slot="{ open }"
        :class="[error?.[field] ? 'border-red-600' : 'border-zinc-300', title ? 'mt-1' : '']"
        class="w-full h-10 px-3 py-2 bg-white rounded-lg shadow-sm border cursor-pointer flex items-center justify-between focus:outline-none focus:ring-rose-300 focus:border-rose-300"
      >
        <span>{{ selectedItem?.name ?? "選択してください" }}</span>
        <chevron-down-icon class="size-4 transition-transform duration-200" :class="{ 'rotate-180': open }" />
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
            ><span>{{ item.name }}</span></listbox-option
          >
        </listbox-options>
      </transition>
    </listbox>
    <div v-if="error?.[field]" class="mt-0.5">
      <span class="text-xs text-red-600">{{ error[field] }}</span>
    </div>
  </div>
</template>
