<script setup lang="ts">
import { VueDatePicker } from "@vuepic/vue-datepicker";
import { DateTime } from "luxon";

type Mode = "date" | "time" | "datetime";

const {
  required = false,
  minDate = new Date(),
  timePicker = false,
  format = "yyyy年MM月dd日",
  multiCalendars = 0,
  mode = "date",
} = defineProps<{
  modelValue: string;
  field: string;
  title?: string;
  required?: boolean;
  error?: Record<string, string>;
  minDate?: Date | null;
  timePicker?: boolean;
  format?: string;
  multiCalendars?: number;
  mode?: Mode;
}>();

defineEmits<{
  "update:modelValue": [value: string];
}>();

const formatDateTime = (date: Date, mode: "date" | "time" | "datetime"): string => {
  const dt = DateTime.fromJSDate(date);
  if (mode === "date") return dt.toFormat("yyyy-MM-dd");
  if (mode === "time") return dt.toFormat("HH:mm");
  return dt.toFormat("yyyy-MM-dd HH:mm");
};
</script>

<template>
  <div class="form-date-time-input">
    <label v-if="title" class="text-sm font-medium text-zinc-800 flex gap-1">
      {{ title }}<span v-if="required" class="text-red-500">※</span>
    </label>
    <vue-date-picker
      no-today
      auto-apply
      :class="title ? 'mt-1' : ''"
      :model-value="modelValue"
      :min-date="minDate ?? undefined"
      :time-picker="timePicker"
      :time-config="{ enableTimePicker: timePicker }"
      :formats="{ input: format }"
      :multi-calendars="multiCalendars"
      :week-start="0"
      :action-row="{
        showSelect: false,
        showCancel: false,
        showNow: false,
        showPreview: false,
      }"
      :ui="{
        dayClass: (date) => {
          const d = date.getDay();
          if (d === 0) return 'dp__day_sunday';
          if (d === 6) return 'dp__day_saturday';
          return '';
        },
      }"
      @update:model-value="(v) => $emit('update:modelValue', formatDateTime(v, mode))"
      @date-click=""
    />
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
</style>
