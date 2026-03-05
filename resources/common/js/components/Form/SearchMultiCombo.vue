<script setup lang="ts">
import { computed, ref, watch } from "vue";
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxLabel,
  ComboboxOption,
  ComboboxOptions,
} from "@headlessui/vue";
import { ChevronDownIcon, CheckIcon } from "@heroicons/vue/24/outline";
import type { EnumType } from "@common/lib";

const LOAD_MORE_THRESHOLD = 90;
const ITEM_HEIGHT = 40;
const VISIBLE_COUNT = 8;

const model = defineModel<(string | number)[]>();
const search = defineModel<string>("search");

const { items } = defineProps<{
  field: string;
  title?: string;
  required?: string;
  showClear?: boolean;
  items: EnumType[];
  error?: Record<string, string>;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: (string | number)[]];
  loadMore: [];
}>();

const scrollTop = ref<number>(0);
const startIndex = computed(() => Math.max(0, Math.floor(scrollTop.value / ITEM_HEIGHT)));
const endIndex = computed(() => Math.min(items.length, startIndex.value + VISIBLE_COUNT));
const visibleItems = computed(() => items.slice(startIndex.value, endIndex.value));
const paddingTop = computed(() => startIndex.value * ITEM_HEIGHT);
const paddingBottom = computed(() => (items.length - endIndex.value) * ITEM_HEIGHT);
const selectedItems = computed(() =>
  items.filter((item) => (model.value ?? []).some((id) => Number(id) === Number(item.id))),
);

const displayValue = (value: unknown): string => {
  if (!Array.isArray(value) || !value.length) return "";
  return (
    value
      .map((id) => items.find((i) => Number(i.id) === Number(id))?.name)
      .filter(Boolean)
      .join(", ") || ""
  );
};

const onOptionsScroll = (e: Event): void => {
  const el = e.target as HTMLElement;
  scrollTop.value = el.scrollTop;
  if (el.scrollTop + el.clientHeight >= el.scrollHeight - LOAD_MORE_THRESHOLD) emit("loadMore");
};

const onSearchInput = (e: Event): void => {
  const value = (e.target as HTMLInputElement).value;
  search.value = value;
};

watch(
  () => model.value,
  () => {
    search.value = "";
  },
);
</script>

<template>
  <div>
    <combobox v-model="model" multiple as="div" v-slot="{ open }">
      <combobox-label v-if="title" class="flex items-center font-medium text-sm text-zinc-800"
        >{{ title }}<span v-if="required" class="text-red-500">※</span></combobox-label
      >
      <div class="relative mt-1">
        <combobox-button
          as="div"
          class="relative w-full h-10 cursor-pointer rounded-lg border border-zinc-300 bg-white shadow-sm flex items-center focus-within:ring-rose-300 focus-within:border-rose-300"
        >
          <combobox-input
            :display-value="displayValue"
            :readonly="selectedItems.length > 0"
            placeholder="すべて"
            autocomplete="off"
            class="w-full rounded-lg border-0 truncate bg-transparent px-3 py-2 focus:ring-0 focus:outline-none"
            @change="onSearchInput"
            @input="onSearchInput"
          />
          <div class="pointer-events-none inset-0 flex justify-end items-center pr-3">
            <div class="pointer-events-auto flex items-center gap-2">
              <button
                type="button"
                class="px-2 py-1 whitespace-nowrap rounded-lg border border-zinc-300 hover:bg-zinc-200 transition ease-in-out duration-300 text-xs cursor-pointer"
                @click.stop="emit('update:modelValue', [])"
              >
                クリア
              </button>
              <chevron-down-icon class="size-4 transition-transform duration-200" :class="{ 'rotate-180': open }" />
            </div>
          </div>
        </combobox-button>
        <transition enter-active-class="animate-fade-in" leave-active-class="animate-fade-out">
          <combobox-options
            v-show="open"
            static
            class="absolute z-50 w-full mt-1 bg-white rounded-lg border border-zinc-200 shadow-lg max-h-60 overflow-scroll"
            @scroll="onOptionsScroll"
          >
            <div v-if="items.length === 0" class="px-3 py-4 text-center text-sm text-zinc-500">入力中...</div>
            <div v-else :style="{ paddingTop: paddingTop + 'px', paddingBottom: paddingBottom + 'px' }">
              <combobox-option
                v-for="item in visibleItems"
                :key="item.id"
                :value="item.id"
                class="px-3 py-2 hover:bg-zinc-100 cursor-pointer"
              >
                <div class="flex justify-between items-center">
                  <span>{{ item.name }}</span>
                  <span v-if="selectedItems.some((s) => s.id === item.id)"><check-icon class="size-4" /></span>
                </div>
              </combobox-option>
            </div>
          </combobox-options>
        </transition>
      </div>
    </combobox>
  </div>
</template>
