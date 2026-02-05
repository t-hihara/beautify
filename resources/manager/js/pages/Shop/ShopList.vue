<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

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
              <div class="flex flex-col items-center">
                <span>メールアドレス</span>
                <span>電話場号</span>
              </div>
            </th>
            <th scope="col" class="px-4 py-3">
              <div class="flex flex-col items-center">
                <span>郵便番号</span>
                <span>都道府県</span>
                <span>住所</span>
                <span>番地・建物名</span>
              </div>
            </th>
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
                <div class="flex flex-col items-center">
                  <span>{{ shop.email }}</span>
                  <span>{{ shop.phone }}</span>
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col items-center">
                  <span class="text-xs">{{ shop.zipcode }}</span>
                  <span class="text-xs">{{ shop.prefecture }}</span>
                  <span class="text-xs">{{ shop.address }}</span>
                  <span class="text-xs">{{ shop.building ?? "----" }}</span>
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
  </div>
</template>
