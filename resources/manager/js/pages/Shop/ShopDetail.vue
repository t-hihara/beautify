<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ArrowLeftIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import { ButtonIcon, TextLink } from "@/common/js/components/Ui/ButtonIndex";
import { computed } from "vue";
import ShopDetailTop from "./Detail/ShopDetailTop.vue";
import ShopDetailStaff from "./Detail/ShopDetailStaff.vue";

export type ImageType = {
  id: number;
  filePath: string;
  fileName: string;
};

export type ShopType = {
  id: number;
  name: string;
  email: string;
  phone: string;
  prefecture: string;
  zipcode: string;
  address: string;
  building: string | null;
  description: string | null;
  activeFlag: string;
  staffCount: number;
  images: ImageType[];
  staffs: StaffType[];
};

export type StaffType = {
  id: number;
  name: string;
  position: string;
  description: string | null;
  image: ImageType | null;
};

const RouteToTab: Record<string, string> = {
  "admin.shops.show": "top",
  "admin.shops.staff": "staff",
};

const TabList = [
  { key: "top", label: "サロンTOP", route: "admin.shops.show" },
  { key: "staff", label: "スタッフ", route: "admin.shops.staff" },
];

defineProps<{
  shop: ShopType;
}>();

const activeTab = computed(() => RouteToTab[route().current() ?? ""] ?? "top");

const tabComponentNames = ["ShopDetailTop", "ShopDetailStaff"];
const currentTabComponent = computed(() => {
  const map: Record<string, typeof ShopDetailTop | typeof ShopDetailStaff> = {
    top: ShopDetailTop,
    staff: ShopDetailStaff,
  };
  return map[activeTab.value] ?? ShopDetailTop;
});
</script>

<template>
  <div>
    <Head title="店舗詳細" />
    <div class="space-y-8">
      <div class="flex items-center justify-between">
        <text-link
          :href="route('admin.shops.index')"
          class="inline-flex items-center gap-1.5 text-zinc-600 hover:text-zinc-800"
        >
          <arrow-left-icon class="size-4" />
          店舗一覧へ戻る
        </text-link>
        <button-icon :href="route('admin.shops.edit', { shop: shop.id })" class="flex items-center gap-2">
          <pencil-square-icon class="size-5" />
          編集
        </button-icon>
      </div>
      <section class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="space-y-1">
          <p class="text-rose-600 text-sm font-medium">{{ shop.prefecture }}</p>
          <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 tracking-tight">
            {{ shop.name }}
          </h2>
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <div class="flex items-center gap-2 text-zinc-600 text-sm">
              <span class="font-medium text-zinc-800">口コミ評価</span>
              <span class="text-zinc-400">—</span>
              <span class="text-zinc-400 text-xs">（評価機能は後から追加予定）</span>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-2">
          <span
            class="inline-flex items-center gap-1.5 rounded-full border px-3 py-2 text-xs"
            :class="
              shop.activeFlag === '有効'
                ? 'border-rose-300 bg-rose-50 text-rose-800'
                : 'border-zinc-300 bg-zinc-50 text-zinc-800'
            "
          >
            運営状態: {{ shop.activeFlag }}
          </span>
        </div>
      </section>
      <section>
        <nav class="flex gap-4 border-b border-zinc-200">
          <Link
            v-for="tab in TabList"
            :key="tab.key"
            :href="route(tab.route, { shop: shop.id })"
            class="px-1 py-3 text-sm font-medium border-b-2 -mb-px transition-colors"
            :class="
              activeTab === tab.key
                ? 'border-rose-500 text-rose-500'
                : 'border-transparent text-zinc-800 hover:text-zinc-600'
            "
            >{{ tab.label }}</Link
          >
        </nav>
      </section>
      <section>
        <KeepAlive :include="tabComponentNames">
          <component :is="currentTabComponent" :key="activeTab" :shop="shop" :staffs="shop.staffs ?? []" />
        </KeepAlive>
      </section>
    </div>
  </div>
</template>
