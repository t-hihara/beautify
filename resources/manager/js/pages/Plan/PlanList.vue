<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import { route } from "ziggy-js";
import { debounce } from "lodash";
import { useGuard } from "@manager/composables/useGuard";
import {
  SearchText,
  SearchSingleSelect,
  SearchMultiSelect,
  SearchDateTime,
  SearchMultiCombo,
} from "@/common/js/components/Form/SearchIndex";
import {
  ButtonPrimary,
  ButtonTertiary,
  ButtonIcon,
  ButtonIconDanger,
  TextLink,
} from "@/common/js/components/Ui/ButtonIndex";
import { FolderArrowDownIcon, PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import type { EnumType, PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";
import DialogModal from "@/common/js/components/Layout/DialogModal.vue";
import axios from "axios";

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
  duration: number;
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
  activeFlag: string | null;
  shopIds: number[];
  types: string[];
  validFrom: string;
  validTo: string;
  perPage: number;
};

const { filters, plans } = defineProps<{
  filters: Filters;
  activeFlags: EnumType[];
  menuTypes: EnumType[];
  plans: PlanType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
  errors?: Record<string, string>;
}>();

const searchForm = useForm<SearchFormType>("PlanListSearch", {
  name: filters.name || "",
  activeFlag: filters.activeFlag || null,
  shopIds: filters.shopIds || [],
  types: filters.types || [],
  validFrom: filters.validFrom || "",
  validTo: filters.validTo || "",
  perPage: filters.perPage || 10,
});

const guard = useGuard();
const shops = ref<EnumType[]>([]);
const shopSearchQuery = ref<string>("");
const offset = ref<number>(0);
const showDeleteModal = ref<boolean>(false);
const targetPlan = ref<PlanType | null>(null);

const fetchShops = async (): Promise<void> => {
  const response = await axios.get("/api/shopsForSearch", {
    params: { limit: 10, offset: 0, name: shopSearchQuery.value },
  });
  shops.value = response.data.shops ?? [];
  offset.value += shops.value.length;
};

const fetchMoreShops = async (): Promise<void> => {
  const response = await axios.get("/api/shopsForSearch", {
    params: { limit: 10, offset: offset.value, name: shopSearchQuery.value },
  });
  shops.value = [...shops.value, ...response.data.shops];
  offset.value += response.data.shops.length;
};

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

const deleteMenu = (): void => {
  router.delete(route(`${guard.value}.plans.delete`, targetPlan.value?.id), {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      closeDeleteModal();
    },
  });
};

const openDeleteModal = (id: number): void => {
  const plan = plans.find((plan) => plan.id === id);
  targetPlan.value = plan ?? null;
  showDeleteModal.value = true;
};

const closeDeleteModal = (): void => {
  showDeleteModal.value = false;
};

const resetTargetMenu = (): void => {
  targetPlan.value = null;
};

onMounted(async () => {
  await fetchShops();
});

watch(
  shopSearchQuery,
  debounce(() => {
    fetchShops();
  }, 300),
);

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
        <search-multi-combo
          v-if="guard === 'admin'"
          v-model="searchForm.shopIds"
          v-model:search="shopSearchQuery"
          field="shopIds"
          title="店舗"
          show-clear
          class="col-span-2"
          :items="shops"
          @load-more="fetchMoreShops"
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
            auto-apply
            :min-date="null"
          />
          <span class="flex h-10 shrink-0 items-center justify-center self-end">〜</span>
          <search-date-time
            v-model="searchForm.validTo"
            field="toDate"
            title="作成日(終了)"
            class="flex-1"
            auto-apply
            :min-date="null"
          />
        </div>
      </div>
      <div
        v-if="errors && Object.keys(errors).length > 0"
        class="inline-block mt-6 p-4 bg-red-100/50 rounded-lg border border-red-200"
      >
        <ul class="list-none list-inside space-y-1">
          <li v-for="(error, key) in errors" :key="key" class="text-sm text-red-600">{{ error }}</li>
        </ul>
      </div>
    </div>
    <div class="mt-6 flex items-end justify-between">
      <search-single-select
        v-model="searchForm.perPage"
        title="表示件数"
        field="perPage"
        class="max-w-28 w-full"
        :items="PER_PAGE_OPTIONS"
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
              <td class="px-4 py-3 text-center">{{ plan.id }}</td>
              <td class="px-4 py-3">
                <div class="flex flex-col gap-y-1">
                  <text-link :href="guard === 'admin' ? route('admin.shops.show', plan.shop.id) : route('shop.index')">
                    <span>{{ plan.shop.name }}</span>
                  </text-link>
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
                  <span>{{ plan.duration }}分</span>
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
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  <button-icon :href="route(`${guard}.plans.edit`, plan.id)"
                    ><pencil-square-icon class="size-6"
                  /></button-icon>
                  <button-icon-danger @click="openDeleteModal(plan.id)">
                    <trash-icon class="size-6" />
                  </button-icon-danger>
                </div>
              </td>
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
    <dialog-modal
      v-model="showDeleteModal"
      title="メニュー削除"
      show-close
      dialog-class="max-w-2xl w-full"
      @close="closeDeleteModal"
      @after-leave="resetTargetMenu"
    >
      <div class="mt-4">
        <div v-if="targetPlan" class="flex flex-col space-y-1">
          <span>該当のメニューを削除しますか？</span>
          <span class="font-semibold">{{ targetPlan.name }}</span>
        </div>
        <div class="mt-4 pt-4 flex justify-center items-center gap-4 border-t border-zinc-200">
          <button-tertiary @click="closeDeleteModal">キャンセル</button-tertiary>
          <button-primary @click="deleteMenu">削除する</button-primary>
        </div>
      </div>
    </dialog-modal>
  </div>
</template>
