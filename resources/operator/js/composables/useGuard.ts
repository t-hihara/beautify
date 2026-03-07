import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export const useGuard = () => {
  const page = usePage();
  const guard = computed(() => page.props.guard as "admin" | "shop");

  return guard;
};
