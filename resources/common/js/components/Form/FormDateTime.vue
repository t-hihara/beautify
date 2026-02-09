<script setup lang="ts">
import { VueDatePicker } from "@vuepic/vue-datepicker";
import { DateTime } from "luxon";
import { XMarkIcon } from "@heroicons/vue/24/outline";

type Mode = "date" | "time" | "datetime";

const {
  required = false,
  minDate = new Date(),
  timePicker = false,
  autoApply = false,
  format = "yyyy年MM月dd日",
  multiCalendars = 0,
  mode = "date",
  inputmode = "none",
} = defineProps<{
  modelValue: string | null;
  field: string;
  title?: string;
  required?: boolean;
  error?: Record<string, string>;
  minDate?: Date | null;
  timePicker?: boolean;
  autoApply?: boolean;
  format?: string;
  multiCalendars?: number;
  mode?: Mode;
  inputmode?: "search" | "text" | "none" | "tel" | "url" | "email" | "numeric" | "decimal";
}>();

const emit = defineEmits<{
  "update:modelValue": [value: string | null];
}>();

const formatDateTime = (date: Date, mode: "date" | "time" | "datetime"): string => {
  const dt = DateTime.fromJSDate(date);
  if (mode === "date") return dt.toFormat("yyyy-MM-dd");
  if (mode === "time") return dt.toFormat("HH:mm");
  return dt.toFormat("yyyy-MM-dd HH:mm");
};

const emitValue = (v: Date | string | null): void => {
  if (v == null) {
    emit("update:modelValue", null);
    return;
  }
  if (mode === "time" && typeof v === "string") {
    emit("update:modelValue", v);
    return;
  }
  emit("update:modelValue", formatDateTime(v as Date, mode));
};
</script>

<template>
  <div class="form-date-time-input">
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <vue-date-picker
      no-today
      :auto-apply="autoApply"
      :model-value="modelValue"
      :model-type="timePicker ? format : undefined"
      :text-input="timePicker ? true : false"
      :min-date="minDate ?? undefined"
      :time-picker="timePicker"
      :time-config="{ enableTimePicker: timePicker }"
      :formats="{ input: format }"
      :multi-calendars="multiCalendars"
      :week-start="0"
      :input-attrs="{
        clearable: true,
        inputmode: inputmode,
      }"
      :action-row="{
        showSelect: mode === 'time',
        showCancel: false,
        showNow: false,
        showPreview: false,
        ...(mode === 'time' && { selectButtonLabel: '確定' }),
      }"
      :ui="{
        dayClass: (date) => {
          const d = date.getDay();
          if (d === 0) return 'dp__day_sunday';
          if (d === 6) return 'dp__day_saturday';
          return '';
        },
      }"
      @update:model-value="emitValue"
      @date-click=""
    >
      <template #clear-icon>
        <button type="button" class="form-date-time-input__clear" @click="$emit('update:modelValue', null)">
          <x-mark-icon class="size-4" />
        </button>
      </template>
    </vue-date-picker>
    <div v-if="error?.[field]" class="mt-0.5">
      <span class="text-xs text-red-600">{{ error[field] }}</span>
    </div>
  </div>
</template>

<style scoped>
.form-date-time-input {
  --dp-border-radius: 0.5rem;
}
.form-date-time-input :deep(.dp__input) {
  height: 2.5rem;
  box-shadow:
    0 1px 3px 0 rgb(0 0 0 / 0.1),
    0 1px 2px -1px rgb(0 0 0 / 0.1) !important;
}
.form-date-time-input :deep(.dp__input:hover) {
  border-color: var(--dp-border-color);
}
.form-date-time-input :deep(.dp__input:focus),
.form-date-time-input :deep(.dp__input_focus) {
  outline: none;
  border-color: #fda4af;
  box-shadow: 0 0 0 3px rgb(253 164 175 / 0.5);
}
.form-date-time-input :deep(.dp__day_saturday) {
  color: #2563eb;
}
.form-date-time-input :deep(.dp__day_sunday) {
  color: #ec4899;
}
.form-date-time-input :deep(.form-date-time-input__clear) {
  padding: 0 4px 0 8px;
  margin-left: 4px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 1.25rem;
}
</style>
