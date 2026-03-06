<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { onMounted, onUnmounted, watch } from "vue";
import { useToast } from "vue-toastification";

const page = usePage();
const toast = useToast();

watch(
  () => page.props.flash,
  (flash) => {
    if (!flash) return;

    if (flash.success) {
      toast.success(flash.success, {
        timeout: 3000,
      });
    }
    if (flash.warning) {
      toast.warning(flash.warning, {
        timeout: 3000,
      });
    }
    if (flash.error) {
      toast.error(flash.error, {
        timeout: 3000,
      });
    }
  },
  {
    deep: true,
    immediate: true,
  },
);

onMounted(() => {
  const handler = () => toast.error("認証の有効期限が切れたため、再ログインが必要です。", { timeout: 3000 });
  window.addEventListener("auth-expired", handler);
  onUnmounted(() => window.removeEventListener("auth-expired", handler));
});
</script>

<template></template>
