<script setup lang="ts">
import { PaginationLinkType, PaginationType } from "@/common/js/lib";
import { Head } from "@inertiajs/vue3";

type ShopType = {
  id: number;
  name: string;
  description: string | null;
  mainImage: ImageType;
  plan: PlanType[];
};

type PlanType = {
  id: number;
  name: string;
  duration: string;
  regularPrice: string;
  sellingPrice: string;
  conditionType: string | null;
  validFrom: string | null;
  validTo: string | null;
};

type ImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

defineProps<{
  shops: ShopType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();
</script>

<template>
  <div>
    <Head title="店舗検索一覧" />
    <div></div>
  </div>
</template>
