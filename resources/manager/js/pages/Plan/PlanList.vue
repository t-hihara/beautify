<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import type { PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";

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
};

defineProps<{
  plans: PlanType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();
</script>

<template>
  <div>
    <Head title="プラン一覧" />
    <div>
      <h2 class="text-3xl">プラン一覧</h2>
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3 text-center">ID</th>
            <th scope="col" class="px-4 py-3">名前</th>
            <th scope="col" class="px-4 py-3 text-end">
              <div class="flex flex-col">
                <span>所要時間</span>
                <span>定価価格</span>
                <span>販売価格</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">適用条件種別</th>
            <th scope="col" class="px-4 py-3">公開状態</th>
            <th scope="col" class="px-4 py-3">メニュー説明</th>
            <th scope="col" class="px-4 py-3">期間限定</th>
            <th scope="col" class="px-4 py-3 text-end">並び順</th>
            <th scope="col" class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="plans.length > 0">
            <tr v-for="plan in plans" :key="plan.id">
              <td class="px-4 py-3">{{ plan.id }}</td>
              <td class="px-4 py-3">{{ plan.name }}</td>
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
                <span class="line-clamp-2">{{ plan.description }}</span>
              </td>
              <td class="px-4 py-3">
                <template v-if="!plan.validFrom && !plan.validTo">
                  <span>----</span>
                </template>
                <template v-else>
                  <div class="flex flex-col justify-center">
                    <span>{{ plan.validFrom ?? "----" }}</span>
                    <span class="text-center">〜</span>
                    <span>{{ plan.validTo ?? "----" }}</span>
                  </div>
                </template>
              </td>
              <td class="px-4 py-3 text-end">{{ plan.sortOrder }}</td>
              <td class="px-4 py-3"></td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="9" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <pagination :links="links" :pagination="pagination" class="mt-4" />
  </div>
</template>
