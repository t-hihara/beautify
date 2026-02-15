<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { UserIcon } from "@heroicons/vue/24/solid";
import { SearchText, SearchSingleSelect, SearchMultiSelect } from "@/common/js/components/Form/SearchIndex";
import { EnvelopeIcon, BuildingOfficeIcon } from "@heroicons/vue/24/outline";
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
    <drawer v-model="showDrawer" show-close position="right" drawer-class="w-3/4" @close="closeDrawer">
      <div></div>
    </drawer>
  </div>
</template>
