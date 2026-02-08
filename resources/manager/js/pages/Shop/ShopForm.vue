<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import type { EnumType } from "@/common/js/lib";
import { FormText, FormEmail, FormSingleSelect, FormTextarea } from "@/common/js/components/Form/FormIndex";

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
  businessHours: BusinessHour[];
};

type BusinessHour = {
  id?: number;
  dayOfWeek: string;
  openTime: string | null;
  closeTime: string | null;
};

type FormType = {
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
    businessHours: BusinessHour[];
  };
};

const { shop } = defineProps<{
  shop?: ShopType;
  activeFlags: EnumType[];
  prefectures: EnumType[];
}>();

const form = useForm<FormType>({
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
    businessHours: shop?.businessHours
      ? shop.businessHours
      : [
          { dayOfWeek: "sunday", openTime: "", closeTime: "" },
          { dayOfWeek: "monday", openTime: "", closeTime: "" },
          { dayOfWeek: "tuesday", openTime: "", closeTime: "" },
          { dayOfWeek: "wednesday", openTime: "", closeTime: "" },
          { dayOfWeek: "thursday", openTime: "", closeTime: "" },
          { dayOfWeek: "friday", openTime: "", closeTime: "" },
          { dayOfWeek: "saturday", openTime: "", closeTime: "" },
        ],
  },
});
</script>

<template>
  <div>
    <Head title="店舗編集" />
    <div>
      <h2 class="text-3xl">店舗編集</h2>
    </div>
    <div class="mt-6">
      <form @submit.prevent="">
        <div class="px-3 py-1 rounded-md bg-zinc-200">
          <p class="text-sm">店舗情報</p>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-6">
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
          <form-textarea
            v-model="form.shop.description"
            title="店舗説明"
            field="shop.description"
            placeholder="店舗説明"
            class="col-span-2"
            :error="form.errors"
          />
        </div>
      </form>
    </div>
  </div>
</template>
