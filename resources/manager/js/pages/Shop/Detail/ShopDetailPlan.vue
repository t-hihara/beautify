<script setup lang="ts">
import { ClockIcon } from "@heroicons/vue/24/outline";
import type { PlanType } from "@manager/pages/Shop/ShopDetail.vue";

defineOptions({ name: "ShopDetailPlan" });
defineProps<{
  shop: { id: number; name: string };
  plans: PlanType[];
}>();
</script>

<template>
  <div class="space-y-4">
    <div class="border-b border-zinc-200 pb-2">
      <h3 class="text-xl font-bold text-zinc-800 border-l-4 border-rose-500 pl-3">プラン</h3>
    </div>
    <div
      v-if="plans.length > 0"
      class="divide-y divide-zinc-200 rounded-xl border border-zinc-200 bg-white overflow-hidden"
    >
      <article v-for="plan in plans" :key="plan.id" class="flex flex-col sm:flex-row gap-4 sm:gap-6 p-4 sm:p-6">
        <div class="sm:w-32 shrink-0 aspect-square rounded-lg overflow-hidden bg-zinc-100 border border-zinc-200">
          <img v-if="plan.image?.filePath" :src="plan.image.filePath" :alt="plan.name" class="size-full object-cover" />
          <div v-else class="size-full flex items-center justify-center text-zinc-400 text-sm">画像なし</div>
        </div>
        <div class="flex-1 min-w-0 flex flex-col gap-2">
          <div class="flex flex-wrap gap-1.5">
            <span
              v-if="plan.conditionType"
              class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-rose-100 text-rose-800"
            >
              {{ plan.conditionType }}
            </span>
            <span
              v-for="menuType in plan.menuTypes ?? []"
              :key="menuType"
              class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-700"
            >
              {{ menuType }}
            </span>
          </div>
          <h4 class="font-bold text-zinc-800 text-base leading-snug">{{ plan.name }}</h4>
          <div class="text-sm">
            <span class="inline-flex items-center gap-1 text-zinc-600">
              <ClockIcon class="size-4 shrink-0" />
              {{ plan.duration }}分
            </span>
            <div class="flex flex-wrap items-baseline gap-2 mt-1">
              <span v-if="plan.regularPrice > plan.sellingPrice" class="text-zinc-500 line-through">
                通常 {{ plan.regularPrice.toLocaleString() }}円
              </span>
              <template v-if="plan.discountPercent !== null">
                <span class="text-zinc-600">{{ plan.discountPercent }}%OFF</span>
                <span class="text-zinc-400">→</span>
              </template>
              <span class="font-bold text-rose-600 text-lg">{{ plan.sellingPrice.toLocaleString() }}円</span>
            </div>
          </div>
        </div>
      </article>
    </div>
    <p v-else class="text-zinc-500 text-sm">プランはありません</p>
  </div>
</template>
