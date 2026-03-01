<script setup lang="ts">
import { PaginationLinkType, PaginationType } from "@/common/js/lib";
import { Head, Link } from "@inertiajs/vue3";

type PlanType = {
  id: number;
  name: string;
  duration: number;
  regularPrice: number;
  sellingPrice: number;
  conditionType: string | null;
  validFrom: string | null;
  validTo: string | null;
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
  <div class="min-h-screen bg-gray-50">
    <Head title="店舗検索一覧" />
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">店舗一覧</h2>

      <section class="space-y-6" aria-label="検索結果の店舗一覧">
        <article
          v-for="shop in shops"
          :key="shop.id"
          class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm flex flex-col sm:flex-row"
        >
          <div class="sm:w-48 shrink-0 aspect-4/3 sm:aspect-square bg-zinc-200">
            <img
              v-if="shop.mainImage"
              :src="shop.mainImage.filePath"
              :alt="`${shop.name}の店舗画像`"
              class="w-full h-full object-cover"
              width="192"
              height="144"
              loading="lazy"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm">画像なし</div>
          </div>
          <div class="flex-1 p-4 sm:p-5 min-w-0">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ shop.name }}</h2>
            <p v-if="shop.description" class="text-sm text-gray-600 line-clamp-2 mb-3">{{ shop.description }}</p>
            <ul v-if="shop.plans.length" class="space-y-2">
              <li
                v-for="plan in shop.plans.slice(0, 3)"
                :key="plan.id"
                class="text-sm text-gray-700 flex flex-wrap items-baseline gap-x-2"
              >
                <span>{{ plan.name }}</span>
                <span v-if="plan.duration">{{ plan.duration }}分</span>
                <span class="font-medium">¥{{ plan.sellingPrice.toLocaleString() }}</span>
              </li>
            </ul>
            <p v-if="shop.plans.length > 3" class="text-sm text-gray-500 mt-1">すべてのプランを見る</p>
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
          class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-sm"
        >
          前へ
        </Link>
        <span class="px-4 py-2 text-sm text-gray-600">
          {{ pagination.currentPage }} / {{ pagination.lastPage }}（全{{ pagination.total }}件）
        </span>
        <Link
          v-if="pagination.next"
          :href="pagination.next"
          class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-sm"
        >
          次へ
        </Link>
      </nav>
    </div>
  </div>
</template>
