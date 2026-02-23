<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import { route } from "ziggy-js";
import { debounce } from "lodash";
import { useGuard } from "@manager/composables/useGuard";
import {
  SearchText,
  SearchSingleSelect,
  SearchMultiSelect,
  SearchDateTime,
} from "@/common/js/components/Form/SearchIndex";
import { ButtonPrimary, TextLink } from "@/common/js/components/Ui/ButtonIndex";
import { FolderArrowDownIcon } from "@heroicons/vue/24/solid";
import type { EnumType, PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type Filters = {
  name: string;
  activeFlag: string;
  shopIds: number[];
  types: string[];
  validFrom: string | null;
  validTo: string | null;
  perPage: number;
};

type PlanType = {
  id: number;
  name: string;
  description: string;
  totalDuration: number;
  regularPrice: number;
  sellingPrice: number;
  conditionType: string | null;
  activeFlag: string;
  sortOrder: number;
  validFrom: string | null;
  validTo: string | null;
  shop: EnumType;
  menus: MenuType[];
};

type MenuType = {
  id: number;
  name: string;
  type: string;
};

type SearchFormType = {
  name: string;
  activeFlag: string;
  shopIds: number[];
  types: string[];
  validFrom: string;
  validTo: string;
  perPage: number;
};

const { filters } = defineProps<{
  filters: Filters;
  activeFlags: EnumType[];
  menuTypes: EnumType[];
  shops: EnumType[];
  plans: PlanType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();

const searchForm = useForm<SearchFormType>({
  name: filters.name || "",
  activeFlag: filters.activeFlag || "active",
  shopIds: filters.shopIds || [],
  types: filters.types || [],
  validFrom: filters.validFrom || "",
  validTo: filters.validTo || "",
  perPage: filters.perPage || 10,
});

const guard = useGuard();

const search = (): void => {
  searchForm.get(route(`${guard.value}.plans.index`), {
    preserveState: true,
    preserveScroll: true,
  });
};

const exportFile = (type: "excel" | "csv"): void => {
  type === "excel"
    ? searchForm.get(route(`${guard.value}.plans.excel`))
    : searchForm.get(route(`${guard.value}.plans.csv`));
};

watch(
  () => searchForm.data(),
  debounce(() => {
    search();
  }, 300),
);
</script>

<template>
  <div>
    <Head title="プラン一覧" />
    <div>
      <h2 class="text-3xl">プラン一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
      <div class="grid grid-cols-4 gap-4">
        <search-text v-model="searchForm.name" field="name" title="プラン名" placeholder="プラン名" />
        <search-multi-select
          v-if="guard === 'admin'"
          v-model="searchForm.shopIds"
          field="shopIds"
          title="店舗"
          show-clear
          :items="shops"
        />
        <search-single-select
          v-model="searchForm.activeFlag"
          title="運営状態"
          field="activeFlag"
          show-all
          :items="activeFlags"
        />
        <search-multi-select
          v-model="searchForm.types"
          title="メニュー種別"
          field="types"
          show-clear
          class="col-span-2"
          :items="menuTypes"
        />
        <div class="col-span-2 flex items-end gap-2">
          <search-date-time
            v-model="searchForm.validFrom"
            field="fromDate"
            title="作成日(開始)"
            class="flex-1"
            :min-date="null"
            :error="searchForm.errors"
          />
          <span class="flex h-10 shrink-0 items-center justify-center self-end">〜</span>
          <search-date-time
            v-model="searchForm.validTo"
            field="toDate"
            title="作成日(終了)"
            class="flex-1"
            :min-date="null"
            :error="searchForm.errors"
          />
        </div>
      </div>
    </div>
    <div class="mt-6 flex items-end justify-between">
      <search-single-select
        v-model="searchForm.perPage"
        title="表示件数"
        field="perPage"
        :items="PER_PAGE_OPTIONS"
        class="max-w-28 w-full"
      />
      <div class="flex items-center gap-2">
        <button-primary @click="exportFile('excel')" class="flex items-center gap-2"
          ><folder-arrow-down-icon class="size-4" />Excel</button-primary
        >
        <button-primary @click="exportFile('csv')" class="flex items-center gap-2"
          ><folder-arrow-down-icon class="size-4" />CSV</button-primary
        >
      </div>
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3 text-center">ID</th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col">
                <span>店舗名</span>
                <span>プラン名</span>
                <span>メニュー</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3 text-end">
              <div class="flex flex-col">
                <span>所要時間</span>
                <span>定価価格</span>
                <span>販売価格</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">適用条件種別</th>
            <th scope="col" class="px-4 py-3">公開状態</th>
            <th scope="col" class="px-4 py-3">期間限定</th>
            <th scope="col" class="px-4 py-3 text-end">並び順</th>
            <th scope="col" class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="plans.length > 0">
            <tr v-for="plan in plans" :key="plan.id">
              <td class="px-4 py-3">{{ plan.id }}</td>
              <td class="px-4 py-3">
                <div class="flex flex-col gap-y-1">
                  <span>{{ plan.shop.name }}</span>
                  <span>{{ plan.name }}</span>
                  <div v-if="plan.menus.length > 0" class="flex flex-wrap gap-1 items-center w-fit">
                    <template v-for="menu in plan.menus.slice(0, 2)" :key="menu.id">
                      <span class="inline-flex items-center rounded-md bg-rose-100 px-2 py-0.5 text-xs text-zinc-800">{{
                        menu.name
                      }}</span>
                    </template>
                    <span v-if="plan.menus.length > 2" class="text-xs text-zinc-500">...</span>
                  </div>
                  <span v-else>----</span>
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col text-end">
                  <span>{{ plan.totalDuration }}分</span>
                  <span>¥{{ plan.regularPrice.toLocaleString() }}</span>
                  <span>¥{{ plan.sellingPrice.toLocaleString() }}</span>
                </div>
              </td>
              <td class="px-4 py-3">{{ plan.conditionType ?? "----" }}</td>
              <td class="px-4 py-3">{{ plan.activeFlag }}</td>
              <td class="px-4 py-3">
                <template v-if="!plan.validFrom && !plan.validTo">
                  <span>----</span>
                </template>
                <template v-else>
                  <div class="inline-flex flex-col items-start">
                    <span :class="plan.validFrom ? 'self-center' : ''">{{ plan.validFrom ?? "----" }}</span>
                    <span class="self-center">〜</span>
                    <span :class="plan.validTo ? 'self-center' : ''">{{ plan.validTo ?? "----" }}</span>
                  </div>
                </template>
              </td>
              <td class="px-4 py-3 text-end">{{ plan.sortOrder }}</td>
              <td class="px-4 py-3"></td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="8" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <pagination :links="links" :pagination="pagination" :per-page="searchForm.perPage" class="mt-4" />
  </div>
</template>
