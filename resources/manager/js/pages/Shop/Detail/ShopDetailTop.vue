<script setup lang="ts">
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ChevronLeftIcon, ChevronRightIcon, ClockIcon, UserGroupIcon } from "@heroicons/vue/24/outline";
import type { ImageType, ShopType, StaffType, PlanType } from "@manager/pages/Shop/ShopDetail.vue";

defineOptions({ name: "ShopDetailTop" });

const { shop } = defineProps<{
  shop: ShopType;
}>();

const currentImageIndex = ref<number>(0);
const images = computed<ImageType[]>(() => shop?.images ?? []);
const hasImages = computed<boolean>(() => (shop?.images?.length ?? 0) > 0);
const displayStaffs = computed<StaffType[]>(() => (shop?.staffs ?? []).slice(0, 4));
const displayPlans = computed<PlanType[]>(() => shop?.plans ?? []);

const goPrev = (): void => {
  if (!hasImages.value) return;
  currentImageIndex.value = currentImageIndex.value <= 0 ? images.value.length - 1 : currentImageIndex.value - 1;
};

const goNext = (): void => {
  if (!hasImages.value) return;
  currentImageIndex.value = currentImageIndex.value >= images.value.length - 1 ? 0 : currentImageIndex.value + 1;
};

const setIndex = (index: number): void => {
  currentImageIndex.value = index;
};
</script>

<template>
  <div class="space-y-8">
    <section class="rounded-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
      <div class="col-span-1 flex flex-col gap-3">
        <div class="rounded-xl overflow-hidden bg-zinc-200 aspect-video relative w-full">
          <template v-if="hasImages">
            <img
              v-for="(img, i) in images"
              :key="img.id"
              :src="img.filePath"
              :alt="img.fileName"
              class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
              :class="i === currentImageIndex ? 'opacity-100 z-0' : 'opacity-0 z-[-1]'"
            />
            <button
              v-if="images.length > 1"
              type="button"
              class="absolute left-2 top-1/2 -translate-y-1/2 z-10 size-9 rounded-full bg-white/90 shadow-md flex items-center justify-center text-zinc-700 hover:bg-white transition-colors"
              aria-label="前の画像"
              @click="goPrev"
            >
              <ChevronLeftIcon class="size-5" />
            </button>
            <button
              v-if="images.length > 1"
              type="button"
              class="absolute right-2 top-1/2 -translate-y-1/2 z-10 size-9 rounded-full bg-white/90 shadow-md flex items-center justify-center text-zinc-700 hover:bg-white transition-colors"
              aria-label="次の画像"
              @click="goNext"
            >
              <ChevronRightIcon class="size-5" />
            </button>
          </template>
          <div v-else class="absolute inset-0 flex items-center justify-center text-zinc-400 text-sm">
            画像はありません
          </div>
        </div>
        <div v-if="images.length > 1" class="flex gap-2 overflow-x-auto pb-1">
          <button
            v-for="(img, i) in images"
            :key="img.id"
            type="button"
            class="shrink-0 aspect-video w-32 h-20 rounded-lg overflow-hidden border-2 transition-colors focus:outline-none"
            :class="i === currentImageIndex ? 'border-rose-500' : 'border-zinc-200 hover:border-zinc-300'"
            @click="setIndex(i)"
          >
            <img :src="img.filePath" :alt="img.fileName" class="w-full h-full object-cover" />
          </button>
        </div>
      </div>
      <div class="col-span-1 lg:aspect-video w-full flex flex-col justify-center lg:min-h-0">
        <h3 class="text-xl sm:text-2xl font-bold text-zinc-900 text-center lg:text-left mb-2">店舗説明</h3>
        <p v-if="shop?.description" class="text-zinc-700 text-sm leading-relaxed whitespace-pre-line">
          {{ shop.description }}
        </p>
        <p v-else class="text-zinc-400 text-sm">説明はありません</p>
      </div>
    </section>
    <section v-if="displayStaffs.length > 0" class="space-y-4">
      <h3 class="text-xl font-bold text-zinc-900 border-l-4 border-rose-500 pl-3">スタッフ</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
        <article
          v-for="staff in displayStaffs"
          :key="staff.id"
          class="flex flex-col h-full bg-white rounded-lg overflow-hidden border border-zinc-200"
        >
          <div
            class="aspect-square w-full shrink-0"
            :class="staff.image?.filePath ? 'bg-zinc-100' : 'bg-zinc-100/70 border-b border-zinc-300'"
          >
            <img
              v-if="staff.image?.filePath"
              :src="staff.image.filePath"
              :alt="staff.name"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-zinc-500 text-sm">写真なし</div>
          </div>
          <div class="p-4 flex flex-col flex-1 min-h-0">
            <p class="font-bold text-zinc-900 text-lg">{{ staff.name }}</p>
            <p class="text-zinc-600 text-sm mt-0.5">{{ staff.position }}</p>
            <div class="mt-3 flex flex-col flex-1 min-h-0">
              <p class="text-zinc-600 text-xs font-medium mb-1.5">スタッフ紹介</p>
              <div class="bg-zinc-100 rounded-lg px-3 py-2.5 flex-1 min-h-18">
                <p class="text-zinc-700 text-sm leading-relaxed line-clamp-3">
                  {{ staff.description ?? "—" }}
                </p>
              </div>
            </div>
          </div>
        </article>
      </div>
      <div class="flex justify-end">
        <Link
          :href="route('admin.shops.staff', { shop: shop.id })"
          class="inline-flex items-center gap-2 rounded-lg border border-rose-500 bg-white px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors"
        >
          <UserGroupIcon class="size-5" />
          スタッフ一覧を表示する
        </Link>
      </div>
    </section>
    <section v-if="displayPlans.length > 0" class="space-y-4">
      <h3 class="text-xl font-bold text-zinc-900 border-l-4 border-rose-500 pl-3">プラン</h3>
      <div class="space-y-0 divide-y divide-zinc-200 rounded-xl border border-zinc-200 bg-white overflow-hidden">
        <article
          v-for="plan in displayPlans"
          :key="plan.id"
          class="flex flex-col sm:flex-row gap-4 sm:gap-6 p-4 sm:p-6"
        >
          <div
            class="sm:w-32 shrink-0 aspect-square sm:aspect-square rounded-lg overflow-hidden bg-zinc-100 border border-zinc-200"
          >
            <img
              v-if="plan.image?.filePath"
              :src="plan.image.filePath"
              :alt="plan.name"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-zinc-400 text-sm">画像なし</div>
          </div>
          <div class="flex-1 min-w-0 flex flex-col gap-2">
            <div class="flex flex-wrap gap-1.5">
              <span
                v-if="plan.conditionType"
                class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800"
              >
                {{ plan.conditionType }}
              </span>
              <span
                v-for="menuType in (plan.menuTypes ?? [])"
                :key="menuType"
                class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700"
              >
                {{ menuType }}
              </span>
            </div>
            <h3 class="font-bold text-zinc-800 text-base leading-snug">{{ plan.name }}</h3>
            <div class="text-sm">
              <span class="inline-flex items-center gap-1 text-zinc-600">
                <ClockIcon class="size-4 shrink-0" />
                {{ plan.totalDuration }}分
              </span>
              <div class="flex flex-wrap items-baseline gap-2">
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
    </section>
    <section class="rounded-xl bg-white">
      <dl class="divide-y divide-zinc-200">
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">店舗名</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ shop?.name ?? "—" }}</dd>
        </div>
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">住所</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">
            {{
              shop
                ? [shop.zipcode && `〒${shop.zipcode}`, shop.prefecture, shop.address, shop.building]
                    .filter(Boolean)
                    .join(" ") || "—"
                : "—"
            }}
          </dd>
        </div>
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">電話番号</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ shop?.phone ?? "—" }}</dd>
        </div>
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">メールアドレス</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ shop?.email ?? "—" }}</dd>
        </div>
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">運営状態</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ shop?.activeFlag ?? "—" }}</dd>
        </div>
        <div class="flex flex-col sm:flex-row gap-1 sm:gap-6 py-5 sm:py-6 px-5 sm:px-6">
          <dt class="sm:w-28 shrink-0 text-zinc-700 text-sm font-semibold">スタッフ数</dt>
          <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ shop?.staffCount ?? 0 }}人</dd>
        </div>
      </dl>
    </section>
  </div>
</template>
