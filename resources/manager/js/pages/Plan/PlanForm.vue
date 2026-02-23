<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";
import {
  FormText,
  FormNumber,
  FormSingleSelect,
  FormTextarea,
  FormSingleImage,
  FormSwitchToggle,
  FormDateTime,
} from "@/common/js/components/Form/FormIndex";
import type { EnumType } from "@/common/js/lib";

type PlanType = {
  id: number;
  name: string;
  description: string;
  totalDuration: number;
  regularPrice: number;
  sellingPrice: number;
  conditionType: string | null;
  activeFlag: string;
  sortOrder: number;
  validFrom: string | null;
  validTo: string | null;
  image: ImageType | null;
};

type ImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

type MenuType = {
  id: number;
  name: string;
  type: string;
  price: number;
  duration: number;
};

type FormType = {
  _method: "POST" | "PATCH";
  id?: number;
  name: string;
  description: string;
  totalDuration: number;
  regularPrice: number;
  sellingPrice: number;
  conditionType: string | null;
  activeFlag: string;
  sortOrder: number;
  validFrom: string | null;
  validTo: string | null;
  image: File | string | null;
};

const { plan } = defineProps<{
  plan?: PlanType;
  activeFlags: EnumType[];
  conditionTypes: EnumType[];
}>();

const guard = useGuard();
const isEdit = computed<boolean>(() => route().current() === `${guard.value}.plans.edit`);

const form = useForm<FormType>({
  _method: "POST",
  id: plan?.id ?? undefined,
  name: plan?.name ?? "",
  description: plan?.description ?? "",
  totalDuration: plan?.totalDuration ?? 0,
  regularPrice: plan?.regularPrice ?? 0,
  sellingPrice: plan?.sellingPrice ?? 0,
  conditionType: plan?.conditionType ?? "",
  activeFlag: plan?.activeFlag ?? "active",
  sortOrder: plan?.sortOrder ?? 1,
  validFrom: plan?.validFrom ?? null,
  validTo: plan?.validTo ?? null,
  image: (plan?.image?.filePath ?? null) as File | string | null,
});

const activeFlag = computed<boolean>({
  get: () => form.activeFlag === "active",
  set: (v: boolean) => {
    form.activeFlag = v ? "active" : "inactive";
  },
});
</script>

<template>
  <div>
    <Head :title="isEdit ? 'プラン編集' : 'プラン登録'" />
    <div>
      <h2 class="text-3xl">{{ isEdit ? "プラン編集" : "プラン登録" }}</h2>
    </div>
    <div class="mt-6">
      <form action="">
        <div class="px-3 py-1 rounded-md bg-zinc-200">
          <p class="text-sm font-semibold text-zinc-800">プラン情報</p>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
          <form-text
            v-model="form.name"
            title="プラン名"
            field="name"
            placeholder="プラン名"
            required
            :error="form.errors"
          />
          <form-number
            v-model="form.totalDuration"
            title="所要時間"
            field="totalDuration"
            placeholder="0"
            required
            :error="form.errors"
          />
          <form-number
            v-model="form.regularPrice"
            title="通常料金"
            field="regularPrice"
            placeholder="0"
            required
            :error="form.errors"
          />
          <form-number
            v-model="form.sellingPrice"
            title="表示料金"
            field="sellingPrice"
            placeholder="0"
            required
            :error="form.errors"
          />
          <form-single-select
            v-model="form.conditionType"
            title="適用条件"
            field="conditionType"
            :items="conditionTypes"
            :error="form.errors"
          />
          <div class="col-span-2 flex items-end gap-2">
            <form-date-time
              v-model="form.validFrom"
              model="date"
              title="期間限定（開始）"
              field="validForm"
              inputmode="text"
              format="yyyy/mm/dd"
              class="flex-1"
              :disabled="form.conditionType !== 'period'"
            />
            <span class="shrink-0 flex h-10 items-center justify-center text-zinc-400">～</span>
            <form-date-time
              v-model="form.validFrom"
              model="date"
              title="期間限定（終了）"
              field="validForm"
              inputmode="text"
              format="yyyy/mm/dd"
              class="flex-1"
              :disabled="form.conditionType !== 'period'"
            />
          </div>
          <form-switch-toggle v-model="activeFlag" title="公開状態" field="activeFlag" required :error="form.errors" />
          <form-single-image v-model="form.image" field="image" title="プラン画像" :error="form.errors" />
          <form-textarea
            v-model="form.description"
            title="説明"
            field="description"
            placeholder="説明"
            class="col-span-2"
            :error="form.errors"
          />
        </div>
        <div class="mt-8 pt-8 border-t border-zinc-200 flex justify-end">
          <button-submit>{{ isEdit ? "更新する" : "登録する" }}</button-submit>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
