<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { route } from "ziggy-js";
import { ArrowLeftIcon, ChevronLeftIcon, ChevronRightIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import { ButtonIcon, TextLink } from "@/common/js/components/Ui/ButtonIndex";

type ShopType = {
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
  images: ShopImageType[];
  staffs: StaffType[];
};

type ShopImageType = {
  id: number;
  filePath: string;
  fileName: string;
};

type StaffType = {
  id: number;
  name: string;
  position: string;
  description: string | null;
  image: StaffImageType | null;
};

type StaffImageType = {
  id: number;
  filePath: string;
  fileName: string;
};

const { shop } = defineProps<{
  shop: ShopType;
}>();

const currentImageIndex = ref(0);
const images = computed(() => shop?.images ?? []);
const hasImages = computed(() => (shop?.images?.length ?? 0) > 0);

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

const fullAddress = computed(() => {
  if (!shop) return "—";
  const parts = [] as string[];
  if (shop.zipcode) parts.push(`〒${shop.zipcode}`);
  const line2 = [shop.prefecture, shop.address, shop.building].filter(Boolean).join(" ");
  if (line2) parts.push(line2);
  return parts.length > 0 ? parts.join(" ") : "—";
});

const staffs = computed(() => shop?.staffs ?? []);
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
                <chevron-left-icon class="size-5" />
              </button>
              <button
                v-if="images.length > 1"
                type="button"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 size-9 rounded-full bg-white/90 shadow-md flex items-center justify-center text-zinc-700 hover:bg-white transition-colors"
                aria-label="次の画像"
                @click="goNext"
              >
                <chevron-right-icon class="size-5" />
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
              class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-colors focus:outline-none"
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
      <section v-if="staffs.length > 0" class="space-y-4">
        <div class="border-b border-zinc-200 pb-2">
          <h3 class="text-xl font-bold text-zinc-900 border-l-4 border-rose-500 pl-3">スタッフ</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
          <article
            v-for="staff in staffs"
            :key="staff.id"
            class="flex flex-col h-full bg-white rounded-lg overflow-hidden"
          >
            <div
              class="aspect-square w-full shrink-0"
              :class="staff.image?.filePath ? 'bg-zinc-100' : 'bg-zinc-100/70 border border-zinc-300'"
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
              <p class="text-zinc-500 text-xs mt-2">口コミ評価 —（後で実装）</p>
              <div class="mt-3 flex flex-col flex-1 min-h-0">
                <p class="text-zinc-600 text-xs font-medium mb-1.5">スタッフ紹介</p>
                <div class="bg-zinc-100 rounded-lg px-3 py-2.5 flex-1 min-h-18">
                  <p class="text-zinc-700 text-sm leading-relaxed">
                    {{ staff.description ?? "—" }}
                  </p>
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
            <dd class="text-zinc-900 text-sm sm:flex-1 min-w-0">{{ fullAddress }}</dd>
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
        </dl>
      </section>
    </div>
  </div>
</template>
