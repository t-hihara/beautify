<script setup lang="ts">
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { route } from "ziggy-js";
import { DateTime } from "luxon";
import { ButtonPrimary, ButtonSecondary } from "@/common/js/components/Ui/ButtonIndex";
import { CalendarDaysIcon, ChevronRightIcon, MapPinIcon } from "@heroicons/vue/24/outline";

import { SearchDateTime } from "@/common/js/components/Form/SearchIndex";
import ModalDialog from "@common/components/Layout/DialogModal.vue";

type SearchShopFormType = {
  date: string | null;
  prefectureIds: number[];
  areaIds: number[];
};

type PrefectureType = {
  id: number;
  name: string;
  areas: {
    id: number;
    name: string;
  }[];
};

defineProps<{
  prefecturesWithAreas: PrefectureType[];
  prefecturesWithoutAreas: PrefectureType[];
}>();

const searchShopForm = useForm<SearchShopFormType>({
  date: null,
  prefectureIds: [],
  areaIds: [],
});

const showSelectAreaModal = ref<boolean>(false);
const showSelectDateModal = ref<boolean>(false);
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Head title="トップページ" />

    <!-- ヒーローセクション -->
    <section class="bg-slate-900 text-white py-8 md:py-12 lg:py-16">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">美容室予約システム</h1>
          <p class="text-lg md:text-xl mb-6 md:mb-8">あなたにぴったりのサロンを見つけよう</p>
          <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
            <button-primary class="px-6 py-2.5 sm:px-8 sm:py-3 text-base sm:text-lg w-full sm:w-auto">
              予約を始める
            </button-primary>
            <button-secondary class="px-6 py-2.5 sm:px-8 sm:py-3 text-base sm:text-lg w-full sm:w-auto">
              店舗を探す
            </button-secondary>
          </div>
        </div>
      </div>
    </section>

    <!-- 検索セクション -->
    <section class="bg-gray-100 py-8 md:py-12">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">SEARCH</h2>
          <p class="text-sm md:text-base text-gray-600 text-center mb-6 md:mb-8">ヘアサロンを探す</p>
          <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6">
            <button
              class="bg-pink-200 hover:bg-pink-300 text-gray-800 font-medium py-4 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <span>今日の予約</span>
              <chevron-right-icon class="size-4" />
            </button>
            <button
              class="bg-pink-200 hover:bg-pink-300 text-gray-800 font-medium py-4 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <span>明日の予約</span>
              <chevron-right-icon class="size-4" />
            </button>
          </div>

          <div class="mt-6 space-y-4">
            <div
              @click="showSelectDateModal = true"
              class="bg-white rounded-lg p-4 flex justify-between items-center cursor-pointer"
            >
              <div class="flex items-center gap-2">
                <calendar-days-icon class="size-5" />
                <span v-if="searchShopForm.date" class="text-zinc-800">{{
                  DateTime.fromISO(searchShopForm.date).toFormat("yyyy年MM月dd日")
                }}</span>
                <span v-else class="text-zinc-500">日付を選択</span>
              </div>
            </div>
            <div
              @click="showSelectAreaModal = true"
              class="bg-white rounded-lg p-4 flex items-center gap-3 cursor-pointer"
            >
              <map-pin-icon class="size-5" />
              <span v-if="searchShopForm.areaIds.length > 0" class="flex-1 text-zinc-800 line-clamp-1">test</span>
              <span v-else class="flex-1 text-zinc-500">エリアから探す</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <modal-dialog
      v-model="showSelectDateModal"
      title="日付選択"
      show-close
      dialog-class="max-h-[60vh] flex flex-col"
      @close="showSelectDateModal = false"
    >
      <div class="flex-1 overflow-auto">
        <search-date-time
          v-model="searchShopForm.date"
          field="date"
          format="yyyy年MM月dd日"
          inputmode="text"
          auto-apply
          inline
          class="mt-2"
          :multi-calendars="2"
          @update:model-value="showSelectDateModal = false"
        />
      </div>
    </modal-dialog>

    <modal-dialog
      v-model="showSelectAreaModal"
      title="エリア選択"
      show-close
      dialog-class="w-full max-w-4xl max-h-[60vh] flex flex-col"
      @close="showSelectAreaModal = false"
    >
      <div class="mt-4 pr-3 flex-1 overflow-y-auto space-y-3">
        <template v-for="prefecture in prefecturesWithAreas" :key="prefecture.id">
          <template v-if="prefecture.areas.length > 0">
            <div class="border-t border-zinc-200 pt-3 first:border-t-0">
              <div class="bg-zinc-200 p-3 rounded-md">
                <span class="text-zinc-800">{{ prefecture.name }}</span>
              </div>
              <div class="mt-4 grid grid-cols-3 gap-3">
                <div v-for="area in prefecture.areas" :key="area.id">
                  <label class="flex items-center gap-2">
                    <input v-model="searchShopForm.areaIds" type="checkbox" :value="area.id" />
                    <span class="text-zinc-800">{{ area.name }}</span>
                  </label>
                </div>
              </div>
            </div>
          </template>
        </template>
        <div class="border-t border-zinc-200 pt-3 first:border-t-0">
          <div class="bg-zinc-200 p-3 rounded-md">
            <span>その他都道府県</span>
          </div>
          <div class="mt-4 grid grid-cols-3 gap-3">
            <template v-for="prefecture in prefecturesWithoutAreas" :key="prefecture.id">
              <label class="flex items-center gap-2">
                <input v-model="searchShopForm.prefectureIds" type="checkbox" :value="prefecture.id" />
                <span class="text-zinc-800">{{ prefecture.name }}</span>
              </label>
            </template>
          </div>
        </div>
        <div class="flex items-center justify-center gap-4 p-4 border-t border-zinc-200">
          <button-secondary>選択解除</button-secondary>
          <button-primary>エリアを確定する</button-primary>
        </div>
      </div>
    </modal-dialog>
  </div>
</template>
