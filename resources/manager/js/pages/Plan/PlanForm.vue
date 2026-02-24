<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { computed, watch } from "vue";
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
  menus: PlanMenuType[];
};

type ImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

type PlanMenuType = {
  id: number;
  name: string;
  type: string;
  price: number;
  duration: number;
};

type MenuType = {
  label: string;
  items: {
    id: number;
    name: string;
    type: string;
    price: number;
    duration: number;
  }[];
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
  menuIds: number[];
};

const { plan, menus } = defineProps<{
  plan?: PlanType;
  activeFlags: EnumType[];
  conditionTypes: EnumType[];
  menus: MenuType[];
}>();

const guard = useGuard();

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
  menuIds: plan?.menus?.map((menu) => menu.id) ?? [],
});

const isEdit = computed<boolean>(() => route().current() === `${guard.value}.plans.edit`);
const activeFlag = computed<boolean>({
  get: () => form.activeFlag === "active",
  set: (v: boolean) => {
    form.activeFlag = v ? "active" : "inactive";
  },
});
const menuItems = computed<MenuType["items"][number][]>(() => menus.flatMap((group) => group.items));
const selectedMenus = computed<MenuType["items"][number][]>(() =>
  form.menuIds
    .map((menuId) => menuItems.value.find((menu) => menu.id === menuId))
    .filter((menu): menu is MenuType["items"][number] => menu != null),
);
const totalDuration = computed<number>(() => selectedMenus.value.reduce((sum, menu) => sum + menu.duration, 0));
const regularPrice = computed<number>(() => selectedMenus.value.reduce((sum, menu) => sum + menu.price, 0));

const addMenu = (menuItem: MenuType["items"][number]): void => {
  if (form.menuIds.includes(menuItem.id)) return;
  form.menuIds = [...form.menuIds, menuItem.id];
};
const removeMenu = (menuId: number): void => {
  form.menuIds = form.menuIds.filter((id) => id !== menuId);
};

const submit = (): void => {
  if (isEdit.value) {
    form._method = "PATCH";
    form.post(route(`${guard.value}.plans.update`, form.id));
  } else {
    //
  }
};

watch(
  totalDuration,
  (value) => {
    form.totalDuration = value;
  },
  { immediate: true },
);

watch(
  regularPrice,
  (value) => {
    form.regularPrice = value;
  },
  { immediate: true },
);
</script>

<template>
  <div>
    <Head :title="isEdit ? 'プラン編集' : 'プラン登録'" />
    <div>
      <h2 class="text-3xl">{{ isEdit ? "プラン編集" : "プラン登録" }}</h2>
    </div>
    <div class="mt-6">
      <form @submit.prevent="submit">
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
            :disabled="true"
            :error="form.errors"
          />
          <form-number
            v-model="form.regularPrice"
            title="通常料金"
            field="regularPrice"
            placeholder="0"
            required
            :disabled="true"
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
        <div class="mt-8 pt-8 border-t border-zinc-200">
          <div class="px-3 py-1 rounded-md bg-zinc-200">
            <p class="text-sm font-semibold text-zinc-800">プランに含まれるメニュー</p>
          </div>
          <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-lg border border-zinc-200 bg-zinc-50/50 p-4">
              <h3 class="text-sm font-medium text-zinc-600">選択中のメニュー</h3>
              <ul class="mt-3 space-y-2">
                <li
                  v-for="(menu, index) in selectedMenus"
                  :key="menu.id"
                  class="flex items-center justify-between gap-2 rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm"
                >
                  <div class="flex items-center gap-2">
                    <span
                      class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-zinc-200 text-xs text-zinc-600"
                    >
                      {{ index + 1 }}
                    </span>
                    <span class="font-medium text-zinc-800">{{ menu.name }}</span>
                    <span class="text-zinc-500">{{ menu.type }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="shrink-0 text-zinc-400">
                      {{ menu.duration }}分 · ¥{{ menu.price?.toLocaleString() }}
                    </span>
                    <button
                      type="button"
                      class="shrink-0 rounded p-1 text-zinc-400 hover:bg-zinc-200 hover:text-zinc-600 cursor-pointer"
                      @click="removeMenu(menu.id)"
                    >
                      ×
                    </button>
                  </div>
                </li>
                <div
                  v-if="selectedMenus.length === 0"
                  class="rounded-md border border-dashed border-zinc-300 bg-white/50] px-3 py-4"
                >
                  <span class="text-center text-sm text-zinc-400">メニューを右側から追加してください</span>
                </div>
              </ul>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-zinc-50/50 p-4">
              <h3 class="text-sm font-medium text-zinc-600">メニューを追加</h3>
              <div class="mt-3 space-y-3">
                <details
                  v-for="{ label, items } in menus"
                  :key="label"
                  class="group rounded-md border border-zinc-200 bg-white"
                >
                  <summary
                    class="flex cursor-pointer list-none items-center justify-between px-3 py-2 text-sm font-medium text-zinc-800 [&::-webkit-details-marker]:hidden"
                  >
                    <span>{{ label }}</span>
                    <div class="flex items-center gap-2">
                      <span class="text-xs text-zinc-500">（{{ items.length }}件）</span>
                      <span class="transition group-open:rotate-180">▼</span>
                    </div>
                  </summary>
                  <ul class="border-t border-zinc-100 p-2">
                    <li
                      v-for="menu in items"
                      :key="menu.id"
                      class="flex cursor-pointer items-center justify-between rounded px-2 py-1.5 text-sm hover:bg-zinc-100"
                      :class="form.menuIds.includes(menu.id) && 'bg-rose-200/50 pointer-events-none'"
                      @click="addMenu(menu)"
                    >
                      <span class="font-medium text-zinc-800">{{ menu.name }}</span>
                      <span class="text-zinc-500">{{ menu.duration }}分 · ¥{{ menu.price?.toLocaleString() }}</span>
                    </li>
                  </ul>
                </details>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-8 pt-8 border-t border-zinc-200 flex justify-end">
          <button-submit>{{ isEdit ? "更新する" : "登録する" }}</button-submit>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
