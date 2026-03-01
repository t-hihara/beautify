<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ClockIcon, MapPinIcon } from "@heroicons/vue/24/outline";
import type { PaginationLinkType, PaginationType } from "@/common/js/lib";

type PlanType = {
  id: number;
  name: string;
  duration: number;
  regularPrice: number;
  sellingPrice: number;
  conditionType: string | null;
  validFrom: string | null;
  validTo: string | null;
  discountPercent: number | null;
};

type MainImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

type ShopType = {
  id: number;
  name: string;
  description: string | null;
  prefecture: string;
  zipcode: string;
  address: string;
  building: string | null;
  mainImage: MainImageType | null;
  plans: PlanType[];
};

defineProps<{
  shops: ShopType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();
</script>

<template>
  <div class="min-h-screen bg-zinc-50">
    <Head title="店舗検索一覧" />
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
      <h2 class="text-2xl md:text-3xl font-bold text-zinc-800">店舗一覧</h2>

      <section class="mt-6 space-y-6" aria-label="検索結果の店舗一覧">
        <article v-for="shop in shops" :key="shop.id" class="bg-white border border-zinc-200 overflow-hidden shadow-sm">
          <div class="sm:flex sm:items-start">
            <div class="sm:w-48 shrink-0 aspect-video sm:aspect-square bg-zinc-200">
              <img
                v-if="shop.mainImage"
                :src="shop.mainImage.filePath"
                :alt="`${shop.name}の店舗画像`"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-zinc-400 text-sm">画像なし</div>
            </div>
            <div class="flex-1 p-4 sm:p-5 min-w-0">
              <h2 class="text-lg font-semibold text-zinc-800">{{ shop.name }}</h2>
              <div class="flex items-center gap-1 mt-2">
                <map-pin-icon class="size-4" />
                <p class="text-xs sm:text-sm text-zinc-600">
                  {{ shop.prefecture }}{{ shop.address }}{{ shop.building }}
                </p>
              </div>
              <div v-if="shop.description" class="mt-4 sm:mt-2">
                <p class="text-sm text-zinc-600 line-clamp-2">{{ shop.description }}</p>
              </div>
              <div v-else>
                <p class="text-sm text-zinc-600">店舗説明なし</p>
              </div>
            </div>
          </div>
          <div v-if="shop.plans.length" class="border-t border-zinc-200 p-4 sm:p-5 space-y-3">
            <div v-for="plan in shop.plans" :key="plan.id" class="bg-zinc-100 rounded-lg p-3 sm:p-4 text-sm">
              <div class="flex flex-wrap gap-1.5">
                <span
                  v-if="plan.conditionType"
                  class="inline-flex items-center rounded border border-rose-500 bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700"
                >
                  {{ plan.conditionType }}
                </span>
              </div>
              <p class="mt-2 text-md sm:text-xl text-zinc-800 font-medium">{{ plan.name }}</p>
              <div class="mt-2 flex flex-wrap items-center justify-between gap-x-2 gap-y-1">
                <span v-if="plan.duration" class="text-zinc-600 flex items-center gap-1">
                  <clock-icon class="size-4 shrink-0" />{{ plan.duration }}分
                </span>
                <div class="flex items-center gap-2">
                  <template v-if="plan.regularPrice && plan.discountPercent">
                    <span class="text-zinc-500">通常¥{{ plan.regularPrice.toLocaleString() }}⇒</span>
                    <span class="text-rose-600">{{ plan.discountPercent }}％OFF</span>
                  </template>
                  <span class="font-bold text-rose-600">¥{{ plan.sellingPrice.toLocaleString() }}</span>
                </div>
              </div>
            </div>
            <div class="border-t border-zinc-200 pt-2 sm:pt-4">
              <p class="text-sm text-zinc-500 flex items-center justify-center gap-1">
                条件に合うプランをすべて見る
                <span aria-hidden="true">→</span>
              </p>
            </div>
          </div>
        </article>
      </section>

      <nav
        v-if="pagination.lastPage > 1"
        class="mt-8 flex flex-wrap items-center justify-center gap-2"
        aria-label="ページネーション"
      >
        <Link
          v-if="pagination.prev"
          :href="pagination.prev"
          class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50 text-sm"
        >
          前へ
        </Link>
        <span class="px-4 py-2 text-sm text-zinc-600">
          {{ pagination.currentPage }} / {{ pagination.lastPage }}（全{{ pagination.total }}件）
        </span>
        <Link
          v-if="pagination.next"
          :href="pagination.next"
          class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50 text-sm"
        >
          次へ
        </Link>
      </nav>
    </div>
  </div>
</template>
