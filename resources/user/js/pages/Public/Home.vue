<script setup lang="ts">
import { Head, Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ButtonPrimary, ButtonSecondary } from "@/common/js/components/Ui/ButtonIndex";
import { ChevronRightIcon } from "@heroicons/vue/24/outline";
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
            <button-primary
              href="/reservations/create"
              class="px-6 py-2.5 sm:px-8 sm:py-3 text-base sm:text-lg w-full sm:w-auto"
            >
              予約を始める
            </button-primary>
            <button-secondary href="/shops" class="px-6 py-2.5 sm:px-8 sm:py-3 text-base sm:text-lg w-full sm:w-auto">
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
          <!-- タイトル -->
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">SEARCH</h2>
          <p class="text-sm md:text-base text-gray-600 text-center mb-6 md:mb-8">ヘアサロンを探す</p>

          <!-- クイック予約ボタン -->
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
        </div>
      </div>
    </section>
  </div>
</template>
