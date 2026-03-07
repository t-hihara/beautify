<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { throttle } from "lodash";
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

const model = defineModel<string | number | null>();
const search = defineModel<string>("search");

const ITEM_HEIGHT = 40;
const VISIBLE_COUNT = 8;

const { items } = defineProps<{
  field: string;
  title?: string;
  required?: boolean;
  showClear?: boolean;
  items: EnumType[];
  error?: Record<string, string>;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: string | number | null];
}>();

const scrollTop = ref<number>(0);

const filteredItems = computed(() =>
  search.value?.trim() === ""
    ? items
    : items.filter((item) =>
        String(item.name)
          .toLocaleLowerCase()
          .includes(search.value?.toLocaleLowerCase() ?? ""),
      ),
);
const selectedItem = computed(() => items.find((item) => Number(item.id) === Number(model.value)) ?? null);
const startIndex = computed(() => Math.max(0, Math.floor(scrollTop.value / ITEM_HEIGHT)));
const endIndex = computed(() => Math.min(filteredItems.value.length, startIndex.value + VISIBLE_COUNT));
const visibleItems = computed(() => filteredItems.value.slice(startIndex.value, endIndex.value));
const paddingTop = computed(() => startIndex.value * ITEM_HEIGHT);
const paddingBottom = computed(() => (filteredItems.value.length - endIndex.value) * ITEM_HEIGHT);

const displayValue = (): string => selectedItem.value?.name ?? "";

const onSearchInput = (e: Event): void => {
  search.value = (e.target as HTMLInputElement).value;
};

const onOptionsScroll = (e: Event): void => {
  const el = e.target as HTMLElement;
  scrollTop.value = el.scrollTop;
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
    <combobox v-model="model" as="div" v-slot="{ open }">
      <combobox-label v-if="title" class="flex items-center font-medium text-sm text-zinc-800">
        {{ title }} <span v-if="required" class="text-red-500">※</span>
      </combobox-label>
      <div class="relative mt-1">
        <combobox-button
          as="div"
          class="relative w-full h-10 cursor-pointer rounded-lg border border-zinc-300 bg-white shadow-sm flex items-center focus-within:ring-rose-300 focus-within:border-rose-300"
        >
          <combobox-input
            :display-value="displayValue"
            placeholder="すべて"
            autocomplete="off"
            class="w-full rounded-lg border-0 truncate bg-transparent px-3 py-2 focus:ring-0 focus:outline-none"
            @input="onSearchInput"
            @change="onSearchInput"
          />
          <div class="pointer-events-none flex justify-end items-center pr-3">
            <div class="pointer-events-auto flex items-center gap-2">
              <button
                type="button"
                class="px-2 py-1 whitespace-nowrap rounded-lg border border-zinc-300 hover:bg-zinc-200 transition ease-in-out duration-300 text-xs cursor-pointer"
                @click.stop="emit('update:modelValue', null)"
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
            <template v-if="filteredItems.length > 0">
              <div :style="{ paddingTop: paddingTop + 'px', paddingBottom: paddingBottom + 'px' }">
                <combobox-option
                  v-for="item in visibleItems"
                  :key="item.id"
                  :value="item.id"
                  class="px-3 py-2 hover:bg-zinc-100 cursor-pointer"
                >
                  <span>{{ item.name }}</span>
                </combobox-option>
              </div>
            </template>
            <template v-else-if="(search ?? '').trim() !== ''">
              <div class="px-3 py-4 text-center text-sm text-zinc-500">入力中...</div>
            </template>
            <template v-else-if="items.length === 0">
              <div class="px-3 py-4 text-center text-sm text-zinc-500">候補がありません</div>
            </template>
            <template v-else>
              <div class="px-3 py-4 text-center text-sm text-zinc-500">該当する店舗がありません。</div>
            </template>
          </combobox-options>
        </transition>
      </div>
    </combobox>
  </div>
</template>
