<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { DateTime } from "luxon";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import { SearchText, SearchDateTime, SearchSingleSelect } from "@/common/js/components/Form/SearchIndex";
import { ClipboardDocumentListIcon } from "@heroicons/vue/24/outline";
import { ButtonSubmit, ButtonText, ButtonTertiary } from "@/common/js/components/Ui/ButtonIndex";
import type { PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";
import DialogModal from "@/common/js/components/Layout/DialogModal.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type FilterType = {
  name: string;
  event: string;
  description: string;
  fromDate: string;
  toDate: string;
  perPage: number;
};

type LogType = {
  id: number;
  description: string;
  event: string;
  causer: string | null;
  createdAt: string;
  properties: PropertyType[];
};

type PropertyType = {
  attribute: string;
  oldValue: string | null;
  newValue: string | null;
};

type SearchFormType = {
  name: string;
  event: string;
  description: string;
  fromDate: string | null;
  toDate: string | null;
  perPage: number;
};

const guard = useGuard();
const showDetailModal = ref<boolean>(false);
const targetProperties = ref<PropertyType[]>([]);
const { filters, logs } = defineProps<{
  filters: FilterType;
  logs: LogType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();

const searchForm = useForm<SearchFormType>({
  name: filters.name || "",
  event: filters.event || "",
  description: filters.description || "",
  fromDate: filters.fromDate || DateTime.now().minus({ month: 1 }).toISODate(),
  toDate: filters.toDate || null,
  perPage: filters.perPage || 10,
});

const search = (): void => {
  searchForm.get(route(`${guard.value}.logs.index`), {
    preserveState: true,
    preserveScroll: true,
  });
};

const resetSearch = (): void => {
  searchForm.name = "";
  searchForm.event = "";
  searchForm.description = "";
  searchForm.fromDate = DateTime.now().toISODate();
  searchForm.toDate = "";
  searchForm.perPage = 10;
  search();
};

const openDialog = (id: number): void => {
  const log = logs.find((log) => log.id === id);
  targetProperties.value = log?.properties ?? [];
  showDetailModal.value = true;
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
        <search-text v-model="searchForm.description" field="description" title="内容" placeholder="内容で検索" />
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
          <div class="flex items-center gap-2">
            <button-submit :disabled="searchForm.processing">検索する</button-submit>
            <button-tertiary @click="resetSearch" :disabled="searchForm.processing">リセット</button-tertiary>
          </div>
        </form>
      </div>
    </div>
    <div class="mt-6 max-w-28">
      <search-single-select v-model="searchForm.perPage" title="表示件数" field="perPage" :items="PER_PAGE_OPTIONS" />
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">実行者名</th>
            <th scope="col" class="px-4 py-3">イベント</th>
            <th scope="col" class="px-4 py-3">内容</th>
            <th scope="col" class="px-4 py-3 text-center">詳細</th>
            <th scope="col" class="px-4 py-3">作成日時</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="logs.length > 0">
            <tr v-for="log in logs" :key="log.id">
              <td class="px-4 py-3">{{ log.id }}</td>
              <td class="px-4 py-3">{{ log.causer ?? "----" }}</td>
              <td class="px-4 py-3">{{ log.event }}</td>
              <td class="px-4 py-3">{{ log.description }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-center">
                  <button-text @click="openDialog(log.id)"><clipboard-document-list-icon class="size-5" /></button-text>
                </div>
              </td>
              <td class="px-4 py-3">{{ log.createdAt }}</td>
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
    <pagination :links="links" :pagination="pagination" :per-page="Number(filters.perPage)" class="mt-4" />
    <dialog-modal
      v-model="showDetailModal"
      title="変更詳細"
      show-close
      dialog-class="max-w-4xl w-full"
      @close="showDetailModal = false"
    >
      <div class="mt-4 rounded-lg border border-zinc-200 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-300 text-sm">
          <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
            <tr>
              <th scope="col" class="px-4 py-3">項目</th>
              <th scope="col" class="px-4 py-3">変更前</th>
              <th scope="col" class="px-4 py-3">変更後</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-300 text-zinc-600">
            <tr v-for="row in targetProperties" :key="row.attribute">
              <td class="px-4 py-3">{{ row.attribute }}</td>
              <td class="px-4 py-3">{{ row.oldValue ?? "----" }}</td>
              <td class="px-4 py-3">{{ row.newValue ?? "----" }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </dialog-modal>
  </div>
</template>
