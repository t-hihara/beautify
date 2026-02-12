<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { HomeIcon, PhoneArrowUpRightIcon, EnvelopeIcon } from "@heroicons/vue/24/outline";
import { FolderArrowDownIcon, PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import { SearchText, SearchSingleSelect, SearchMultiSelect } from "@/common/js/components/Form/SearchIndex";
import { ButtonPrimary, ButtonTertiary, ButtonIcon, ButtonIconDanger } from "@/common/js/components/Ui/ButtonIndex";
import type { PaginationType, PaginationLinkType, EnumType } from "@/common/js/lib";
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
  email: string;
  phone: string;
  prefectureIds: number[];
  activeFlag: string | null;
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
  businessHours: BusinessHour[];
};

type BusinessHour = {
  id: number;
  dayOfWeek: string;
  openTime: string | null;
  closeTime: string | null;
};

type SearchFormType = {
  name: string;
  email: string;
  phone: string;
  prefectureIds: number[];
  activeFlag: string | null;
  perPage: number;
};

const showDeleteModal = ref<boolean>(false);
const targetShop = ref<ShopType | null>(null);

const { filters, shops } = defineProps<{
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
  activeFlag: filters.activeFlag || null,
  perPage: Number(filters.perPage) || 10,
});

const search = (): void => {
  searchForm.get(route("admin.shops.index"), {
    preserveState: true,
    preserveScroll: true,
  });
};

const exportFile = (type: "excel" | "csv"): void => {
  type === "excel" ? searchForm.get(route("admin.shops.excel")) : searchForm.get(route("admin.shops.csv"));
};

const openDeleteModal = (id: number): void => {
  const shop = shops.find((shop) => shop.id === id);
  targetShop.value = shop ?? null;
  showDeleteModal.value = true;
};

const closeDeleteModal = (): void => {
  showDeleteModal.value = false;
};

const resetTargetShop = (): void => {
  targetShop.value = null;
};

const deleteShop = (): void => {
  router.delete(route("admin.shops.delete", targetShop.value?.id), {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      closeDeleteModal();
    },
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
          show-all
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
    <div class="mt-6 flex items-end justify-between">
      <search-single-select
        v-model="searchForm.perPage"
        title="表示件数"
        field="perPage"
        :items="PER_PAGE_OPTIONS"
        class="max-w-28"
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
            <th scope="col" class="px-4 py-3">店舗名</th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col">
                <span>メールアドレス</span>
                <span>電話場号</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">店舗住所</th>
            <th scope="col" class="px-4 py-3">営業時間</th>
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
                <div class="flex flex-col space-y-1">
                  <span class="flex items-center gap-1"><envelope-icon class="size-3 mt-0.5" />{{ shop.email }}</span>
                  <span class="flex items-center gap-1"
                    ><phone-arrow-up-right-icon class="size-3 mt-0.5" />{{ shop.phone }}</span
                  >
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col space-y-1">
                  <span class="text-xs">〒{{ shop.zipcode }}</span>
                  <span class="text-xs flex items-center gap-0.5"
                    ><home-icon class="size-3" />{{ shop.prefecture }}</span
                  >
                  <span class="text-xs indent-3.5">{{ shop.address }}</span>
                  <span class="text-xs indent-3.5">{{ shop.building ?? "----" }}</span>
                </div>
              </td>
              <td class="px-4 py-3 space-y-0.5">
                <div v-for="businessHour in shop.businessHours" :key="businessHour.id" class="flex items-center gap-2">
                  <span class="text-xs">{{ businessHour.dayOfWeek }}</span>
                  <template v-if="businessHour.openTime && businessHour.closeTime">
                    <span class="text-xs">{{ businessHour.openTime }} ~ {{ businessHour.closeTime }}</span>
                  </template>
                  <template v-else>
                    <span class="text-xs text-rose-600">休業日</span>
                  </template>
                </div>
              </td>
              <td class="px-4 py-3 text-center">{{ shop.activeFlag }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end items-center gap-2">
                  <button-icon :href="route('admin.shops.edit', shop.id)"
                    ><pencil-square-icon class="size-6"
                  /></button-icon>
                  <button-icon-danger @click="openDeleteModal(shop.id)"
                    ><trash-icon class="size-6"
                  /></button-icon-danger>
                </div>
              </td>
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
    <pagination :links="links" :pagination="pagination" :per-page="Number(filters.perPage) || 10" class="mt-4" />
    <dialog-modal
      v-model="showDeleteModal"
      title="店舗削除"
      show-close
      dialog-class="max-w-2xl w-full"
      @close="closeDeleteModal"
      @after-leave="resetTargetShop"
    >
      <div class="mt-4">
        <div v-if="targetShop" class="flex flex-col space-y-1">
          <span>該当の店舗を削除しますか？</span>
          <span class="font-semibold">{{ targetShop.name }}</span>
        </div>
        <div class="mt-4 pt-4 flex justify-center items-center gap-4 border-t border-zinc-200">
          <button-tertiary @click="closeDeleteModal">キャンセル</button-tertiary>
          <button-primary @click="deleteShop">削除する</button-primary>
        </div>
      </div>
    </dialog-modal>
  </div>
</template>
