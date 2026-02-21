<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import type { PaginationType, PaginationLinkType } from "@/common/js/lib";

const {
  links,
  pagination,
  perPage = 10,
} = defineProps<{
  links: PaginationLinkType[];
  pagination: PaginationType;
  perPage?: number;
}>();

const visibleLinks = computed(() => {
  const { currentPage, lastPage } = pagination;
  if (lastPage <= 1) {
    const one = linkByPage(1);
    return one ? [{ type: "page" as const, link: one }] : [];
  }

  const start = Math.max(1, currentPage - 2);
  const end = Math.min(lastPage, currentPage + 2);
  const items: Array<{ type: "page"; link: PaginationLinkType } | { type: "ellipsis" }> = [];

  items.push({ type: "page", link: linkByPage(1)! });

  if (start > 2) {
    items.push({ type: "ellipsis" });
    for (let p = start; p <= end; p++) {
      const link = linkByPage(p);
      if (link) items.push({ type: "page", link });
    }
  } else {
    for (let p = 2; p <= end; p++) {
      const link = linkByPage(p);
      if (link) items.push({ type: "page", link });
    }
  }

  if (end < lastPage) {
    items.push({ type: "ellipsis" });
    const last = linkByPage(lastPage);
    if (last) items.push({ type: "page", link: last });
  }

  return items;
});

const linkByPage = (page: number) => links.find((l) => l.label === String(page));
</script>

<template>
  <div v-if="pagination.lastPage !== 1" class="flex items-center justify-between">
    <div>
      <span class="text-sm text-zinc-600"
        >全 {{ pagination.total }} 件中 {{ (pagination.currentPage - 1) * perPage + 1 }} ~
        {{ Math.min(pagination.currentPage * perPage, pagination.total) }} 件を表示中</span
      >
    </div>
    <nav class="flex -space-x-px">
      <Link v-if="pagination.prev" :href="pagination.prev" class="p-2 border border-zinc-200 bg-white text-zinc-800">
        <span class="inline-flex min-w-6 min-h-6 shrink-0 items-center justify-center text-sm"><</span>
      </Link>
      <template v-for="(item, i) in visibleLinks" :key="i">
        <Link
          v-if="item.type === 'page'"
          :href="item.link.url"
          class="p-2 border border-zinc-200 text-zinc-800"
          :class="item.link.active ? 'bg-rose-200' : 'bg-white'"
        >
          <span class="inline-flex min-w-6 min-h-6 shrink-0 items-center justify-center text-sm">{{
            item.link.label
          }}</span>
        </Link>
        <span
          v-else
          class="flex p-2 items-center justify-center border border-zinc-200 bg-white text-zinc-800 text-sm"
        >
          <span class="inline-flex w-6 h-6 shrink-0 items-center justify-center">...</span>
        </span>
      </template>
      <Link v-if="pagination.next" :href="pagination.next" class="p-2 border border-zinc-200 bg-white text-zinc-800">
        <span class="inline-flex min-w-6 min-h-6 shrink-0 items-center justify-center text-sm">></span>
      </Link>
    </nav>
  </div>
</template>
