<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import {
  FormText,
  FormNumber,
  FormSingleSelect,
  FormTextarea,
  FormSwitchToggle,
} from "@/common/js/components/Form/FormIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";
import type { EnumType } from "@/common/js/lib";

type MenuType = {
  id: number;
  name: string;
  shopId: number;
  type: string;
  price: number;
  duration: number;
  description: string | null;
  activeFlag: string;
  sortOrder: number;
};

type FormType = {
  id?: number;
  shopId: number | null;
  name: string;
  type: string;
  price: number;
  duration: number;
  description: string | null;
  activeFlag: string;
  sortOrder: number;
};

const { menu } = defineProps<{
  menu?: MenuType;
  activeFlags: EnumType[];
  menuTypes: EnumType[];
  shops: EnumType[];
}>();

const guard = useGuard();
const form = useForm<FormType>({
  id: menu?.id ?? undefined,
  shopId: menu?.shopId ?? null,
  name: menu?.name ?? "",
  type: menu?.type ?? "",
  price: menu?.price ?? 0,
  duration: menu?.duration ?? 0,
  description: menu?.description ?? null,
  activeFlag: menu?.activeFlag ?? "active",
  sortOrder: menu?.sortOrder ?? 1,
});

const isEdit = computed<boolean>(() => route().current() === `${guard.value}.menus.edit`);
const activeFlag = computed<boolean>({
  get: () => form.activeFlag === "active",
  set: (v: boolean) => {
    form.activeFlag = v ? "active" : "inactive";
  },
});

const submit = (): void => {
  if (isEdit.value) {
    form.patch(route(`${guard.value}.menus.update`, form?.id));
  } else {
    form.post(route(`${guard.value}.menus.store`));
  }
};
</script>

<template>
  <div>
    <Head :title="isEdit ? 'メニュー編集' : 'メニュー登録'" />
    <div>
      <h2 class="text-3xl">{{ isEdit ? "メニュー編集" : "メニュー登録" }}</h2>
    </div>
    <div class="mt-6">
      <form @submit.prevent="submit">
        <div class="px-3 py-1 rounded-md bg-zinc-200">
          <p class="text-sm font-semibold text-zinc-800">メニュー情報</p>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
          <form-text
            v-model="form.name"
            title="メニュー名"
            field="name"
            placeholder="メニュー名"
            required
            :error="form.errors"
          />
          <form-single-select
            v-if="!isEdit"
            v-model="form.shopId"
            field="shopId"
            title="所属店舗"
            :items="shops"
            :required="!isEdit"
            :error="form.errors"
          />
          <form-single-select
            v-model="form.type"
            title="メニュー種別"
            field="type"
            required
            :items="menuTypes"
            :error="form.errors"
          />
          <form-number
            v-model="form.price"
            title="料金（税抜）"
            field="price"
            placeholder="0"
            :min="0"
            required
            :error="form.errors"
          />
          <form-number
            v-model="form.duration"
            title="所要時間（分）"
            field="duration"
            placeholder="0"
            :min="0"
            required
            :error="form.errors"
          />
          <form-number
            v-model="form.sortOrder"
            title="並び順"
            field="sortOrder"
            placeholder="0"
            :min="0"
            required
            :error="form.errors"
          />
          <form-switch-toggle v-model="activeFlag" title="有効状態" field="activeFlag" required :error="form.errors" />
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
