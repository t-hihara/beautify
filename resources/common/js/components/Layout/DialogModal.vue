<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";

const { showClose = false, dialogClass = "" } = defineProps<{
  modelValue: boolean;
  title?: string;
  showClose?: boolean;
  dialogClass?: string;
}>();

defineEmits<{
  "update:modelValue": [value: boolean];
  "after-leave": [];
  close: [];
}>();
</script>

<template>
  <transition-root :show="modelValue" as="template" @after-leave="$emit('after-leave')">
    <Dialog @close="$emit('close')" class="relative z-50">
      <div class="fixed inset-0 bg-black/50"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <transition-child
          as="template"
          enter="transition ease-out duration-200"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="transition ease-in duration-150"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <dialog-panel class="bg-white rounded-lg p-6" :class="dialogClass">
            <div class="flex justify-between items-center">
              <dialog-title class="text-md font-semibold">{{ title }}</dialog-title>
              <button
                v-if="showClose"
                class="cursor-pointer hover:text-zinc-800 transition ease-in-out duration-300"
                @click="$emit('close')"
              >
                <x-mark-icon class="size-4" />
              </button>
            </div>
            <slot />
          </dialog-panel>
        </transition-child>
      </div>
    </Dialog>
  </transition-root>
</template>
