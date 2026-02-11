<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import type { EnumType } from "@/common/js/lib";
import {
  FormText,
  FormEmail,
  FormSingleSelect,
  FormTextarea,
  FormDateTime,
  FormSwitchToggle,
  FormMultiImage,
} from "@/common/js/components/Form/FormIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";

type ShopType = {
  id: number;
  name: string;
  email: string;
  phone: string;
  prefectureId: number;
  zipcode: string;
  address: string;
  building: string | null;
  description: string | null;
  activeFlag: string;
  updatedAt?: string;
  businessHours: BusinessHour[];
  images: ImageType[];
};

type BusinessHour = {
  id?: number;
  dayOfWeek: string;
  label: string;
  openTime: string | null;
  closeTime: string | null;
};

export type ImageType = {
  id: number;
  filename: string;
  filePath: string;
};

export type ShopImageFormValue = {
  keepImageIds: number[];
  newImages: File[];
};

type FormType = {
  _method: "POST" | "PATCH";
  shop: {
    id: number | null;
    name: string;
    email: string;
    phone: string;
    prefectureId: number | null;
    zipcode: string;
    address: string;
    building: string | null;
    description: string | null;
    activeFlag: string;
    updatedAt?: string;
    businessHours: BusinessHour[];
    keepImageIds: number[];
    newImages: File[];
  };
};

const { shop } = defineProps<{
  shop?: ShopType;
  activeFlags: EnumType[];
  prefectures: EnumType[];
}>();

const DEFAULT_BUSINESS_HOURS: BusinessHour[] = [
  { dayOfWeek: "sunday", label: "日", openTime: null, closeTime: null },
  { dayOfWeek: "tuesday", label: "火", openTime: null, closeTime: null },
  { dayOfWeek: "wednesday", label: "水", openTime: null, closeTime: null },
  { dayOfWeek: "thursday", label: "木", openTime: null, closeTime: null },
  { dayOfWeek: "friday", label: "金", openTime: null, closeTime: null },
  { dayOfWeek: "saturday", label: "土", openTime: null, closeTime: null },
];

const form = useForm<FormType>({
  _method: "POST",
  shop: {
    id: shop?.id || null,
    name: shop?.name || "",
    email: shop?.email || "",
    phone: shop?.phone || "",
    prefectureId: shop?.prefectureId || null,
    zipcode: shop?.zipcode || "",
    address: shop?.address || "",
    building: shop?.building || null,
    description: shop?.description || null,
    activeFlag: shop?.activeFlag || "active",
    updatedAt: shop?.updatedAt || undefined,
    businessHours: shop?.businessHours ?? DEFAULT_BUSINESS_HOURS,
    keepImageIds: shop?.images?.map((img) => img.id) ?? [],
    newImages: [],
  },
});

const isEdit = computed<boolean>(() => route().current("admin.shops.edit"));

const shopImageFormValue = computed<ShopImageFormValue>({
  get: () => ({
    keepImageIds: form.shop.keepImageIds,
    newImages: form.shop.newImages,
  }),
  set: (v: ShopImageFormValue) => {
    form.shop.keepImageIds = v.keepImageIds;
    form.shop.newImages = v.newImages;
  },
});

const activeFlag = computed<boolean>({
  get: () => form.shop.activeFlag === "active",
  set: (v: boolean) => {
    form.shop.activeFlag = v ? "active" : "inactive";
  },
});

const businessHoursErrors = computed<string[][]>(() =>
  form.shop.businessHours.map((_, index) => {
    const prefix = `shop.businessHours.${index}`;
    return Object.entries(form.errors)
      .filter(([key]) => key.startsWith(prefix))
      .map(([, value]) => value as string);
  }),
);

const submit = (): void => {
  // setDummy();
  const options = { forceFormData: true };
  if (isEdit.value) {
    form
      .transform((data) => ({
        _method: "PATCH",
        shop: data.shop,
      }))
      .post(route("admin.shops.update", shop?.id), options);
  } else {
    form.post(route("admin.shops.store"), options);
  }
};

// const dummy = {
//   shop: {
//     name: "テスト店舗",
//     nameKana: "テストテンポ",
//     email: "test2@example.com",
//     phone: "0312345678",
//     activeFlag: "active",
//     prefectureId: 1,
//     zipcode: "1000001",
//     address: "東京都千代田区千代田1-1",
//     building: "テストビル1F",
//     description: "テスト用の店舗説明です",
//     businessHours: [
//       { dayOfWeek: "sunday", label: "日", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "monday", label: "月", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "tuesday", label: "火", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "wednesday", label: "水", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "thursday", label: "木", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "friday", label: "金", openTime: "10:00", closeTime: "20:00" },
//       { dayOfWeek: "saturday", label: "土", openTime: "10:00", closeTime: "20:00" },
//     ],
//     // newImages は送信用の File[]。表示用の画像は送らないのでダミーでは空のまま
//   },
// };

// const setDummy = () => {
//   if (isEdit.value) {
//     //
//   } else {
//     form.shop = { ...form.shop, ...dummy.shop };
//   }
// };
</script>

<template>
  <div>
    <Head :title="isEdit ? '店舗編集' : '店舗登録'" />
    <div>
      <h2 class="text-3xl">{{ isEdit ? "店舗編集" : "店舗登録" }}</h2>
    </div>
    <div class="mt-6">
      <form @submit.prevent="submit">
        <div class="px-3 py-1 rounded-md bg-zinc-200">
          <p class="text-sm font-semibold text-zinc-800">店舗情報</p>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
          <form-text
            v-model="form.shop.name"
            title="店舗名"
            field="shop.name"
            placeholder="店舗名"
            required
            :error="form.errors"
          />
          <form-email
            v-model="form.shop.email"
            title="メールアドレス"
            field="shop.email"
            placeholder="メールアドレス"
            required
            :error="form.errors"
          />
          <form-text
            v-model="form.shop.phone"
            title="電話番号"
            field="shop.phone"
            placeholder="電話番号"
            required
            :error="form.errors"
          />
          <form-single-select
            v-model="form.shop.prefectureId"
            title="都道府県"
            field="shop.prefectureId"
            required
            :items="prefectures"
            :error="form.errors"
          />
          <form-text
            v-model="form.shop.zipcode"
            title="郵便番号"
            field="shop.zipcode"
            placeholder="郵便番号"
            required
            :error="form.errors"
          />
          <form-text
            v-model="form.shop.address"
            title="住所"
            field="shop.address"
            placeholder="住所"
            required
            :error="form.errors"
          />
          <form-text
            v-model="form.shop.building"
            title="番地・部屋番号"
            field="shop.building"
            placeholder="番地・部屋番号"
            required
            :error="form.errors"
          />
          <form-switch-toggle
            v-model="activeFlag"
            title="運営状態"
            field="shop.activeFlag"
            required
            :error="form.errors"
          />
          <form-textarea
            v-model="form.shop.description"
            title="店舗説明"
            field="shop.description"
            placeholder="店舗説明"
            class="col-span-2"
            :error="form.errors"
          />
        </div>
        <div class="mt-8">
          <div class="px-3 py-1 rounded-md bg-zinc-200">
            <p class="text-sm font-semibold text-zinc-800">店舗画像</p>
          </div>
          <div class="mt-4 p-4 rounded-lg bg-white border border-zinc-200">
            <form-multi-image
              v-model="shopImageFormValue"
              field="shop"
              :max-count="5"
              :existing-images="shop?.images ?? []"
              :error="form.errors"
            />
          </div>
        </div>
        <div class="mt-8 rounded-lg">
          <div class="px-3 py-1 rounded-md bg-zinc-200">
            <p class="text-sm font-semibold text-zinc-800">営業時間</p>
          </div>
          <div class="mt-4 grid grid-cols-3 gap-x-6 gap-y-3 text-sm">
            <div
              v-for="(bh, index) in form.shop.businessHours"
              :key="bh.dayOfWeek"
              class="rounded-md border border-zinc-200 bg-white px-3 py-2 shadow-sm"
            >
              <div class="flex items-center gap-2">
                <span class="w-5 shrink-0 font-medium text-zinc-600">{{ bh.label }}</span>
                <form-date-time
                  v-model="bh.openTime"
                  mode="time"
                  inputmode="text"
                  time-picker
                  format="HH:mm"
                  :field="`shop.businessHours.${index}.openTime`"
                />
                <span class="shrink-0 text-zinc-400">～</span>
                <form-date-time
                  v-model="bh.closeTime"
                  mode="time"
                  inputmode="text"
                  time-picker
                  format="HH:mm"
                  :field="`shop.businessHours.${index}.closeTime`"
                />
              </div>
              <div v-if="(businessHoursErrors[index] ?? []).length" class="mt-1.5">
                <span v-for="msg in businessHoursErrors[index] ?? []" :key="msg" class="text-xs text-red-600">{{
                  msg
                }}</span>
              </div>
            </div>
          </div>
          <p class="mt-3 text-xs text-zinc-500">空欄は休業日</p>
        </div>
        <div class="mt-8 pt-8 border-t border-zinc-200 flex justify-end">
          <button-submit>{{ isEdit ? "更新する" : "登録する" }}</button-submit>
        </div>
      </form>
    </div>
  </div>
</template>
