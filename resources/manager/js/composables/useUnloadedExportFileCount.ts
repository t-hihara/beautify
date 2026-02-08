import { usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { useGuard } from "@manager/composables/useGuard";
import { route } from "ziggy-js";

const countRef = ref<number | null>(null);

export const useUnloadedExportFileCount = () => {
  const page = usePage();

  const count = computed(() => {
    if (countRef.value !== null) return countRef.value;
    return (page.props.unloadedExportFileCount as number) ?? 0;
  });

  const refresh = async (): Promise<void> => {
    const guard = useGuard();
    const url = route(`${guard.value}.exports.unloaded-count`);
    const result = await fetch(url, { credentials: "include" });
    if (!result.ok) return;

    const data = await result.json();
    countRef.value = data.unloadedExportFileCount;
  };

  watch(
    () => page.props.unloadedExportFileCount as number | undefined,
    (val) => {
      countRef.value = val ?? 0;
    },
    { immediate: true },
  );

  return {
    count,
    refresh,
  };
};
