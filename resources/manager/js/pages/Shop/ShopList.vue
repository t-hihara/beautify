<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { HomeIcon, PhoneArrowUpRightIcon, EnvelopeIcon } from "@heroicons/vue/24/outline";
import type { PaginationType, PaginationLinkType } from "@/common/js/lib";

import Pagination from "@manager/components/Ui/Pagination.vue";

type Shop = {
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

defineProps<{
  shops: Shop[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();
</script>

<template>
  <div>
    <Head title="店舗一覧" />
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">店舗名</th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col">
                <span>メールアドレス</span>
                <span>電話場号</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">店舗住所</th>
            <th scope="col" class="px-4 py-3">運営状態</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="shops.length > 0">
            <tr v-for="shop in shops" :key="shop.id">
              <td class="px-4 py-3">{{ shop.id }}</td>
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
              <td class="px-4 py-3">{{ shop.activeFlag }}</td>
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
