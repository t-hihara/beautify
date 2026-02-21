<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import { ButtonPrimary, TextLink } from "@/common/js/components/Ui/ButtonIndex";
import { SearchText, SearchSingleSelect, SearchMultiSelect } from "@/common/js/components/Form/SearchIndex";
import { FolderArrowDownIcon, PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import type { PaginationType, PaginationLinkType, EnumType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type FilterType = {
  name: string;
  types: string[];
  shopIds: number[];
  activeFlag: string;
  perPage: number;
};

type MenuType = {
  id: number;
  name: string;
  type: string;
  price: number;
  duration: number;
  description: string | null;
  activeFlag: string;
  sortOrder: number;
  shop: EnumType;
};

type SearchFormType = {
  name: string;
  types: string[];
  shopIds: number[];
  activeFlag: string;
  perPage: number;
};

const { filters } = defineProps<{
  menus: MenuType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
  filters: FilterType;
  menuTypes: EnumType[];
  activeFlags: EnumType[];
  shops: EnumType[];
}>();

const guard = useGuard();
const searchForm = useForm<SearchFormType>({
  name: filters.name || "",
  types: filters.types || [],
  shopIds: filters.shopIds || [],
  activeFlag: filters.activeFlag || "",
  perPage: filters.perPage || 10,
});

const search = (): void => {
  searchForm.get(route(`${guard.value}.menus.index`), {
    preserveState: true,
    preserveScroll: true,
  });
};

const exportFile = (type: "excel" | "csv"): void => {
  type === "excel"
    ? searchForm.get(route(`${guard.value}.menus.excel`))
    : searchForm.get(route(`${guard.value}.menus.csv`));
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
    <Head title="メニュー一覧" />
    <div>
      <h2 class="text-3xl">メニュー一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
      <div class="grid grid-cols-4 gap-4">
        <search-text v-model="searchForm.name" title="メニュー名" field="name" placeholder="メニュー名で検索" />
        <search-multi-select
          v-if="guard === 'admin'"
          v-model="searchForm.shopIds"
          field="shopIds"
          title="店舗"
          show-clear
          :items="shops"
        />
        <search-multi-select
          v-model="searchForm.types"
          title="メニュータイプ"
          field="types"
          show-clear
          :items="menuTypes"
        />
        <search-single-select
          v-model="searchForm.activeFlag"
          title="運営状態"
          field="activeFlag"
          show-all
          :items="activeFlags"
        />
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
                <span>タイプ</span>
                <span>メニュー名</span>
                <span>店舗名</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">料金</th>
            <th scope="col" class="px-4 py-3">所要時間</th>
            <th scope="col" class="px-4 py-3">公開状態</th>
            <th scope="col" class="px-4 py-3">メニュー説明</th>
            <th scope="col" class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="menus.length > 0">
            <tr v-for="menu in menus" :key="menu.id">
              <td class="px-4 py-3 text-center">{{ menu.id }}</td>
              <td class="px-4 py-3">
                <div class="flex flex-col">
                  <span>{{ menu.type }}</span>
                  <span>{{ menu.name }}</span>
                  <text-link :href="guard === 'admin' ? route('admin.shops.show', menu.shop.id) : route('shop.index')">
                    <span>{{ menu.shop.name }}</span>
                  </text-link>
                </div>
              </td>
              <td class="px-4 py-3">¥{{ menu.price.toLocaleString() }}</td>
              <td class="px-4 py-3">{{ menu.duration }}分</td>
              <td class="px-4 py-3">{{ menu.activeFlag }}</td>
              <td class="px-4 py-3">
                <span class="line-clamp-2">{{ menu.description }}</span>
              </td>
              <td class="px-4 py-3"></td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="7" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <pagination :links="links" :pagination="pagination" :per-page="searchForm.perPage" class="mt-4" />
  </div>
</template>

<style scoped></style>
