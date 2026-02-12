<script setup lang="ts">
import { UserIcon } from "@heroicons/vue/24/solid";
import { EnvelopeIcon, BuildingOfficeIcon } from "@heroicons/vue/24/outline";
import type { EnumType, PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";

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

defineProps<{
  shopStaffs: ShopStaffType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
  shops: EnumType[];
  activeFlags: EnumType[];
}>();
</script>

<template>
  <div>
    <Head title="店舗スタッフ一覧" />
    <div>
      <h2 class="text-3xl">店舗スタッフ一覧</h2>
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
            <tr v-for="staff in shopStaffs" :key="staff.id">
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
    <pagination :links="links" :pagination="pagination" :per-page="10" class="mt-4" />
  </div>
</template>
