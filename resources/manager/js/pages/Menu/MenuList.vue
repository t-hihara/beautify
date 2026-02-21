<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import type { PaginationType, PaginationLinkType, EnumType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";

type MenuType = {
  id: number;
  name: string;
  type: string;
  price: number;
  duration: number;
  description: string | null;
  activeFlag: string;
  sortOrder: number;
};

defineProps<{
  menus: MenuType[];
}>();
</script>

<template>
  <div>
    <Head title="メニュー一覧" />
    <div>
      <h2 class="text-3xl">メニュー一覧</h2>
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
              </div>
            </th>
            <th scope="col" class="px-4 py-3">料金</th>
            <th scope="col" class="px-4 py-3">所要時間</th>
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
                </div>
              </td>
              <td class="px-4 py-3">¥{{ menu.price.toLocaleString() }}</td>
              <td class="px-4 py-3">{{ menu.duration }}分</td>
              <td class="px-4 py-3">
                <span class="line-clamp-2">{{ menu.description }}</span>
              </td>
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
  </div>
</template>

<style scoped></style>
