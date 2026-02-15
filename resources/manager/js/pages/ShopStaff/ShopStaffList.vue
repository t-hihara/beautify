<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { UserIcon } from "@heroicons/vue/24/solid";
import { SearchText, SearchSingleSelect, SearchMultiSelect } from "@/common/js/components/Form/SearchIndex";
import { EnvelopeIcon, BuildingOfficeIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
import type { EnumType, PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";
import Drawer from "@manager/components/Ui/Drawer.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type FilterType = {
  name: string;
  shopIds: (string | number)[];
  activeFlag: string;
  positions: string[];
  perPage: number;
};

type ShopStaffType = {
  id: number;
  name: string;
  email: string;
  position: string;
  experienceYears: number;
  description: string;
  activeFlag: string;
  image: ImageType | null;
  shop: EnumType;
};

type ImageType = {
  id: number;
  filePath: string;
  fileName: string;
};

type SearchFormType = {
  name: string;
  shopIds: (string | number)[];
  activeFlag: string | null;
  positions: string[];
  perPage: number;
};

const showDrawer = ref<boolean>(false);
const targetStaff = ref<ShopStaffType | null>(null);
const { filters, shopStaffs } = defineProps<{
  filters: FilterType;
  shopStaffs: ShopStaffType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
  shops: EnumType[];
  activeFlags: EnumType[];
  positions: EnumType[];
}>();

const searchForm = useForm<SearchFormType>({
  name: filters.name || "",
  shopIds: filters.shopIds || [],
  activeFlag: filters.activeFlag || null,
  positions: filters.positions || [],
  perPage: Number(filters.perPage) || 10,
});

const search = (): void => {
  searchForm.get(route("admin.staffs.index"), {
    preserveState: true,
    preserveScroll: true,
  });
};

const openDrawer = (id: number): void => {
  targetStaff.value = shopStaffs.find((staff) => staff.id === id) ?? null;
  showDrawer.value = true;
};

const closeDrawer = (): void => {
  showDrawer.value = false;
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
    <Head title="店舗スタッフ一覧" />
    <div>
      <h2 class="text-3xl">店舗スタッフ一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
      <div class="grid grid-cols-4 gap-4">
        <search-text v-model="searchForm.name" field="name" title="スタッフ名" placeholder="スタッフ名" />
        <search-multi-select
          v-model="searchForm.shopIds"
          field="shopIds"
          title="店舗"
          class="col-span-2"
          show-clear
          :items="shops"
        />
        <search-multi-select
          v-model="searchForm.positions"
          field="positions"
          title="ポジション"
          show-clear
          :items="positions"
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
    <div class="mt-6 flex items-end">
      <search-single-select
        v-model="searchForm.perPage"
        title="表示件数"
        field="perPage"
        :items="PER_PAGE_OPTIONS"
        class="max-w-28 w-full"
      />
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3 text-center">ID</th>
            <th scope="col" class="px-4 py-3 text-center">画像</th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col space-y-1">
                <span>スタッフ名</span>
                <span>メールアドレス</span>
                <span>店舗名</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col space-y-1">
                <span>ポジション</span>
                <span>経歴年数</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">スタッフ紹介</th>
            <th scope="col" class="px-4 py-3">有効状態</th>
            <th scope="col" class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="shopStaffs.length > 0">
            <tr
              v-for="staff in shopStaffs"
              :key="staff.id"
              class="hover:bg-rose-100/50 transition ease-in-out duration-300 cursor-pointer"
              @click="openDrawer(staff.id)"
            >
              <td class="px-4 py-3 text-center">{{ staff.id }}</td>
              <td class="px-4 py-3 text-center">
                <img
                  v-if="staff.image"
                  :src="staff.image.filePath"
                  alt=""
                  class="mx-auto w-16 h-16 aspect-square object-center rounded-md"
                />
                <span v-else class="mx-auto w-16 h-16 aspect-square rounded-md border border-zinc-200">画像なし</span>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col space-y-1">
                  <span class="flex items-center gap-1"><user-icon class="size-3 mt-0.5" />{{ staff.name }}</span>
                  <span class="flex items-center gap-1"><envelope-icon class="size-3 mt-0.5" />{{ staff.email }}</span>
                  <span class="flex items-center gap-1"
                    ><building-office-icon class="size-3 mt-0.5" />{{ staff.shop.name }}</span
                  >
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col space-y-1">
                  <span>{{ staff.position }}</span>
                  <span>{{ staff.experienceYears }}年</span>
                </div>
              </td>
              <td class="px-4 py-3 max-w-52">
                <span class="line-clamp-2">{{ staff.description }}</span>
              </td>
              <td class="px-4 py-3">{{ staff.activeFlag }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2"></div>
              </td>
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
    <drawer
      v-model="showDrawer"
      show-close
      position="right"
      title="スタッフ詳細"
      drawer-class="w-3/4"
      @close="closeDrawer"
    >
      <template v-if="targetStaff">
        <div class="flex flex-col gap-8 px-8 py-12">
          <div class="flex gap-6 items-start">
            <div
              class="shrink-0 w-28 h-28 rounded-xl overflow-hidden bg-zinc-100 flex items-center justify-center ring-1 ring-zinc-200/80"
            >
              <img
                v-if="targetStaff.image"
                :src="targetStaff.image.filePath"
                :alt="targetStaff.name"
                class="w-full h-full object-cover"
              />
              <user-circle-icon v-else class="w-14 h-14 text-zinc-300" />
            </div>
            <div class="min-w-0 flex-1 pt-0.5">
              <h3 class="text-xl font-bold text-zinc-900 tracking-tight">{{ targetStaff.name }}</h3>
              <p class="text-sm text-zinc-500 mt-1">{{ targetStaff.position }}</p>
              <span
                class="inline-block mt-3 px-3 py-1 text-xs font-medium rounded-md"
                :class="
                  targetStaff.activeFlag === '有効'
                    ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-200/60'
                    : 'bg-zinc-100 text-zinc-600'
                "
              >
                {{ targetStaff.activeFlag }}
              </span>
              <p v-if="targetStaff.description" class="mt-3 text-sm text-zinc-600 leading-relaxed line-clamp-2">
                {{ targetStaff.description }}
              </p>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-zinc-700 mb-3 pl-2 border-l-4 border-rose-400">基本情報</h4>
            <table class="w-full text-sm border-collapse rounded-lg overflow-hidden ring-1 ring-zinc-200/80">
              <tbody class="divide-y divide-zinc-200">
                <tr>
                  <th scope="row" class="w-28 shrink-0 px-4 py-3 text-left font-medium text-zinc-500 bg-zinc-50">
                    メール
                  </th>
                  <td class="px-4 py-3 text-zinc-800 bg-white break-all">{{ targetStaff.email }}</td>
                </tr>
                <tr>
                  <th scope="row" class="px-4 py-3 text-left font-medium text-zinc-500 bg-zinc-50">店舗</th>
                  <td class="px-4 py-3 text-zinc-800 bg-white">{{ targetStaff.shop.name }}</td>
                </tr>
                <tr>
                  <th scope="row" class="px-4 py-3 text-left font-medium text-zinc-500 bg-zinc-50">経歴</th>
                  <td class="px-4 py-3 text-rose-600 font-medium bg-white">{{ targetStaff.experienceYears }}年</td>
                </tr>
                <tr>
                  <th scope="row" class="px-4 py-3 text-left font-medium text-zinc-500 bg-zinc-50">運営状態</th>
                  <td class="px-4 py-3 text-rose-600 font-medium bg-white">
                    {{ targetStaff.activeFlag }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="targetStaff.description">
            <h4 class="text-sm font-semibold text-zinc-700 mb-3 pl-2 border-l-4 border-rose-400">スタッフ紹介</h4>
            <div class="px-4 py-3 bg-zinc-50/80 rounded-lg ring-1 ring-zinc-200/60">
              <p class="text-sm text-zinc-700 leading-relaxed whitespace-pre-wrap">
                {{ targetStaff.description }}
              </p>
            </div>
          </div>
        </div>
      </template>
    </drawer>
  </div>
</template>
