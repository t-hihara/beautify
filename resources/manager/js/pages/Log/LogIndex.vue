<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

type Log = {
  id: number;
  description: string;
  event: string;
  causer: string | null;
  createdAt: string;
};

defineProps<{
  logs: Log[];
}>();
</script>

<template>
  <div>
    <Head title="ログ一覧" />
    <div class="">
      <h2 class="text-3xl">ログ一覧</h2>
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">実行者名</th>
            <th scope="col" class="px-4 py-3">イベント</th>
            <th scope="col" class="px-4 py-3">内容</th>
            <th scope="col" class="px-4 py-3">作成日時</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="logs.length > 0">
            <tr v-for="log in logs" :key="log.id">
              <th class="px-4 py-3">{{ log.id }}</th>
              <th class="px-4 py-3">{{ log.causer ?? "----" }}</th>
              <th class="px-4 py-3">{{ log.event }}</th>
              <th class="px-4 py-3">{{ log.description }}</th>
              <th class="px-4 py-3">{{ log.createdAt }}</th>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="5" class="px-4 py-3 text-center">データがありません。</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>
