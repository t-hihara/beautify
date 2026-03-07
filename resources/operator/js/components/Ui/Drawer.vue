<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";

const { showClose = false, position = "left" } = defineProps<{
  modelValue: boolean;
  title?: string;
  showClose?: boolean;
  position?: "left" | "right";
  drawerClass?: string;
}>();

const emit = defineEmits<{
  "update:modelValue": [value: boolean];
  close: [];
}>();
</script>

<template>
  <Teleport to="body">
    <!-- オーバーレイ: フェードのみ（スライドしない） -->
    <Transition
      enter-from-class="opacity-0"
      enter-active-class="transition-opacity duration-300 ease-in-out"
      enter-to-class="opacity-100"
      leave-from-class="opacity-100"
      leave-active-class="transition-opacity duration-300 ease-in-out"
      leave-to-class="opacity-0"
    >
      <div v-if="modelValue" class="fixed inset-0 z-40 bg-black/50" aria-hidden="true" />
    </Transition>

    <!-- パネル: スライドのみ -->
    <Transition
      :enter-from-class="position === 'right' ? 'translate-x-full' : '-translate-x-full'"
      enter-active-class="transition-transform duration-300 ease-in-out"
      enter-to-class="translate-x-0"
      leave-from-class="translate-x-0"
      leave-active-class="transition-transform duration-300 ease-in-out"
      :leave-to-class="position === 'right' ? 'translate-x-full' : '-translate-x-full'"
    >
      <div
        v-if="modelValue"
        class="fixed top-0 h-full bg-white shadow-sm flex flex-col z-50"
        :class="[position === 'right' ? 'right-0' : 'left-0', drawerClass]"
      >
        <div class="flex shrink-0 justify-between items-center border-b border-zinc-200 px-4 py-3">
          <slot name="header" :close="() => emit('close')">
            <span class="text-md font-semibold">{{ title }}</span>
            <button
              v-if="showClose"
              type="button"
              class="cursor-pointer hover:text-zinc-800 transition ease-in-out duration-300"
              @click="emit('close')"
            >
              <x-mark-icon class="size-4" />
            </button>
          </slot>
        </div>
        <div class="flex-1 min-h-0 overflow-auto">
          <slot />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
