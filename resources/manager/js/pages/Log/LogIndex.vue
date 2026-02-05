<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { DateTime } from "luxon";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";

import { SearchText, SearchDateTime } from "@/common/js/components/Form/SearchIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";

type Filter = {
  name: string;
  event: string;
  fromDate: string;
  toDate: string;
};

type Log = {
  id: number;
  description: string;
  event: string;
  causer: string | null;
  createdAt: string;
};

type SearchForm = {
  name: string;
  event: string;
  fromDate: string | null;
  toDate: string | null;
};

const { guard } = useGuard();
const { filters } = defineProps<{
  filters: Filter;
  logs: Log[];
}>();

const searchForm = useForm<SearchForm>({
  name: filters.name || "",
  event: filters.event || "",
  fromDate: filters.fromDate || DateTime.now().minus({ month: 1 }).toISODate(),
  toDate: filters.toDate || null,
});

const search = (): void => {
  searchForm.get(route(`${guard.value}.logs.index`), {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <div>
    <Head title="ログ一覧" />
    <div class="">
      <h2 class="text-3xl">ログ一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-sm">
      <div class="grid grid-cols-4 gap-4">
        <search-text v-model="searchForm.name" field="name" title="実行者名" placeholder="実行者名で検索" />
        <search-text v-model="searchForm.event" field="event" title="イベント名" placeholder="イベント名で検索" />
        <div class="col-span-2 flex items-end gap-2">
          <search-date-time
            v-model="searchForm.fromDate"
            field="fromDate"
            title="作成日(開始)"
            class="flex-1"
            :min-date="null"
          />
          <span class="flex h-10 shrink-0 items-center justify-center self-end">〜</span>
          <search-date-time
            v-model="searchForm.toDate"
            field="toDate"
            title="作成日(終了)"
            class="flex-1"
            :min-date="null"
          />
        </div>
      </div>
      <div
        class="mt-4 pt-4 border-t border-zinc-200 flex items-center gap-2"
        :class="Object.keys(searchForm.errors).length > 0 ? 'justify-between' : 'justify-end'"
      >
        <div
          v-if="Object.keys(searchForm.errors).length > 0"
          class="p-4 bg-red-100/50 rounded-lg border border-red-200"
        >
          <ul class="list-none list-inside space-y-1">
            <li v-for="(error, key) in searchForm.errors" :key="key" class="text-sm text-red-600">{{ error }}</li>
          </ul>
        </div>
        <form @submit.prevent="search">
          <button-submit :disabled="searchForm.processing">検索する</button-submit>
        </form>
      </div>
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">実行者名</th>
            <th scope="col" class="px-4 py-3">イベント</th>
            <th scope="col" class="px-4 py-3">内容</th>
            <th scope="col" class="px-4 py-3">作成日時</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="logs.length > 0">
            <tr v-for="log in logs" :key="log.id">
              <th class="px-4 py-3">{{ log.id }}</th>
              <th class="px-4 py-3">{{ log.causer ?? "----" }}</th>
              <th class="px-4 py-3">{{ log.event }}</th>
              <th class="px-4 py-3">{{ log.description }}</th>
              <th class="px-4 py-3">{{ log.createdAt }}</th>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="5" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>
