<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { HomeIcon, PhoneArrowUpRightIcon, EnvelopeIcon } from "@heroicons/vue/24/outline";
import type { PaginationType, PaginationLinkType, EnumType } from "@/common/js/lib";

import { SearchText, SearchSingleSelect, SearchMultiSelect } from "@/common/js/components/Form/SearchIndex";
import Pagination from "@manager/components/Ui/Pagination.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type FilterType = {
  name: string;
  email: string;
  phone: string;
  prefectureIds: number[];
  activeFlag: string;
  perPage: number;
};

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
};

type SearchFormType = {
  name: string;
  email: string;
  phone: string;
  prefectureIds: number[];
  activeFlag: string;
  perPage: number;
};

const { filters } = defineProps<{
  filters: FilterType;
  activeFlags: EnumType[];
  prefectures: EnumType[];
  shops: ShopType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();

const searchForm = useForm<SearchFormType>({
  name: filters.name || "",
  email: filters.email || "",
  phone: filters.phone || "",
  prefectureIds: filters.prefectureIds || [],
  activeFlag: filters.activeFlag || "",
  perPage: filters.perPage || 20,
});

const search = (): void => {
  searchForm.get(route("admin.shops.index"), {
    preserveState: true,
    preserveScroll: true,
  });
};

watch(
  () => searchForm.data(),
  debounce(() => {
    if (!searchForm.processing) search();
  }, 300),
);
</script>

<template>
  <div>
    <Head title="店舗一覧" />
    <div>
      <h2 class="text-3xl">店舗一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
      <div class="grid grid-cols-4 gap-4">
        <search-text v-model="searchForm.name" title="店舗名" field="name" placeholder="店舗名で検索" />
        <search-text
          v-model="searchForm.email"
          title="メールアドレス"
          field="email"
          placeholder="メールアドレスで検索"
        />
        <search-text v-model="searchForm.phone" title="電話番号" field="phone" placeholder="電話番号で検索" />
        <search-single-select
          v-model="searchForm.activeFlag"
          title="運営状態"
          field="activeFlag"
          :items="activeFlags"
        />
        <search-multi-select
          v-model="searchForm.prefectureIds"
          title="都道府県"
          field="prefectureIds"
          show-clear
          :items="prefectures"
        />
      </div>
    </div>
    <div class="mt-6 max-w-28">
      <search-single-select v-model="searchForm.perPage" title="表示件数" field="perPage" :items="PER_PAGE_OPTIONS" />
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3 text-center">ID</th>
            <th scope="col" class="px-4 py-3">店舗名</th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col">
                <span>メールアドレス</span>
                <span>電話場号</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">店舗住所</th>
            <th scope="col" class="px-4 py-3 text-center">運営状態</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="shops.length > 0">
            <tr v-for="shop in shops" :key="shop.id">
              <td class="px-4 py-3 text-center">{{ shop.id }}</td>
              <td class="px-4 py-3">{{ shop.name }}</td>
              <td class="px-4 py-3">
                <div class="flex flex-col">
                  <span class="flex items-center gap-1"><envelope-icon class="size-3 mt-0.5" />{{ shop.email }}</span>
                  <span class="flex items-center gap-1"
                    ><phone-arrow-up-right-icon class="size-3 mt-0.5" />{{ shop.phone }}</span
                  >
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col">
                  <span class="text-xs">〒{{ shop.zipcode }}</span>
                  <span class="text-xs flex items-center gap-0.5"
                    ><home-icon class="size-3" />{{ shop.prefecture }}</span
                  >
                  <span class="text-xs indent-3.5">{{ shop.address }}</span>
                  <span class="text-xs indent-3.5">{{ shop.building ?? "----" }}</span>
                </div>
              </td>
              <td class="px-4 py-3 text-center">{{ shop.activeFlag }}</td>
              <td class="px-4 py-3"></td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="6" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <pagination :links="links" :pagination="pagination" class="mt-4" />
  </div>
</template>
