<script setup lang="ts">
import type { StaffType } from "@manager/pages/Shop/ShopDetail.vue";
defineOptions({ name: "ShopDetailStaff" });

defineProps<{
  shop: { id: number; name: string };
  staffs: StaffType[];
}>();
</script>

<template>
  <div class="space-y-4">
    <div class="border-b border-zinc-200 pb-2">
      <h3 class="text-xl font-bold text-zinc-900 border-l-4 border-rose-500 pl-3">スタッフ</h3>
    </div>
    <div v-if="staffs.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
      <article v-for="staff in staffs" :key="staff.id" class="flex flex-col h-full bg-white rounded-lg overflow-hidden">
        <div
          class="aspect-square w-full shrink-0"
          :class="staff.image?.filePath ? 'bg-zinc-100' : 'bg-zinc-100/70 border border-zinc-300'"
        >
          <img
            v-if="staff.image?.filePath"
            :src="staff.image.filePath"
            :alt="staff.name"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center text-zinc-500 text-sm">写真なし</div>
        </div>
        <div class="p-4 flex flex-col flex-1 min-h-0">
          <p class="font-bold text-zinc-900 text-lg">{{ staff.name }}</p>
          <p class="text-zinc-600 text-sm mt-0.5">{{ staff.position }}</p>
          <p class="text-zinc-500 text-xs mt-2">口コミ評価 —（後で実装）</p>
          <div class="mt-3 flex flex-col flex-1 min-h-0">
            <p class="text-zinc-600 text-xs font-medium mb-1.5">スタッフ紹介</p>
            <div class="bg-zinc-100 rounded-lg px-3 py-2.5 flex-1 min-h-18">
              <p class="text-zinc-700 text-sm leading-relaxed">{{ staff.description ?? "—" }}</p>
            </div>
          </div>
        </div>
      </article>
    </div>
    <p v-else class="text-zinc-500 text-sm">スタッフはいません</p>
  </div>
</template>
