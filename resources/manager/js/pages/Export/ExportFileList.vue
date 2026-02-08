<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { debounce } from "lodash";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import { SearchText, SearchDateTime, SearchSingleSelect } from "@/common/js/components/Form/SearchIndex";
import { ButtonPrimary, ButtonSecondary, ButtonIconDanger } from "@/common/js/components/Ui/ButtonIndex";
import { TrashIcon } from "@heroicons/vue/24/solid";
import type { PaginationLinkType, PaginationType } from "@/common/js/lib";
import Pagination from "@manager/components/Ui/Pagination.vue";
import DialogModal from "@/common/js/components/Layout/DialogModal.vue";

const PER_PAGE_OPTIONS = [
  { id: 10, name: "10件" },
  { id: 20, name: "20件" },
  { id: 50, name: "50件" },
  { id: 100, name: "100件" },
];

type FilterType = {
  subject: string;
  fromDate: string;
  toDate: string;
  perPage: number;
};

type FileType = {
  id: number;
  subject: string;
  filename: string;
  status: string;
  downloadedAt: string;
  createdAt: string;
};

type SearchFormType = {
  subject: string;
  fromDate: string | null;
  toDate: string | null;
  perPage: number;
};

const { guard } = useGuard();
const { filters } = defineProps<{
  filters: FilterType;
  files: FileType[];
  links: PaginationLinkType[];
  pagination: PaginationType;
}>();

const searchForm = useForm<SearchFormType>({
  subject: filters.subject || "",
  fromDate: filters.fromDate || null,
  toDate: filters.toDate || null,
  perPage: filters.perPage || 10,
});

const showDeleteModal = ref<boolean>(false);
const targetFile = ref<FileType | null>(null);

const statusClass = computed(() => (status: string): string => {
  switch (status) {
    case "準備中":
      return "bg-yellow-100 text-yellow-800";
    case "処理中":
      return "bg-blue-100 text-blue-800";
    case "完了":
      return "bg-green-100 text-green-800";
    case "失敗":
      return "bg-red-100 text-red-800";
    default:
      return "bg-zinc-100 text-zinc-800";
  }
});

const search = (): void => {
  searchForm.get(route(`${guard.value}.exports.index`), {
    preserveState: true,
    preserveScroll: true,
  });
};

const deleteFile = (fileId: number): void => {
  router.delete(route(`${guard.value}.exports.delete`, fileId), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      closeDialogModal();
    },
  });
};

const openDeleteModal = (file: FileType): void => {
  targetFile.value = file;
  showDeleteModal.value = true;
};

const closeDialogModal = (): void => {
  showDeleteModal.value = false;
  targetFile.value = null;
};

const confirmDelete = (): void => {
  if (!targetFile.value) return;

  deleteFile(targetFile.value.id);
};

const downloadFile = async (fileId: number, filename: string): Promise<void> => {
  const result = await fetch(route(`${guard.value}.exports.download`, fileId), { credentials: "include" });
  if (!result.ok) return;

  const blob = await result.blob();
  const blobUrl = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.href = blobUrl;
  link.download = filename;
  link.click();
  URL.revokeObjectURL(blobUrl);
  router.reload();
};

watch(
  () => searchForm.data(),
  debounce(() => {
    if (!searchForm.processing) search();
  }, 300),
);
</script>

<template>
  <div>
    <Head title="出力ファイル一覧" />
    <div>
      <h2 class="text-3xl">出力ファイル一覧</h2>
    </div>
    <div class="mt-8 p-6 bg-white rounded-lg shadow-sm">
      <div class="grid grid-cols-4 gap-4">
        <search-text
          v-model="searchForm.subject"
          field="subject"
          title="対象名"
          placeholder="対象名で検索"
          :error="searchForm.errors"
        />
        <div class="col-span-2 flex items-end gap-2">
          <search-date-time
            v-model="searchForm.fromDate"
            field="fromDate"
            title="作成日(開始)"
            class="flex-1"
            :min-date="null"
            :error="searchForm.errors"
          />
          <span class="flex h-10 shrink-0 items-center justify-center self-end">〜</span>
          <search-date-time
            v-model="searchForm.toDate"
            field="toDate"
            title="作成日(終了)"
            class="flex-1"
            :min-date="null"
            :error="searchForm.errors"
          />
        </div>
      </div>
    </div>
    <div class="mt-6 max-w-28">
      <search-single-select v-model="searchForm.perPage" title="表示件数" field="perPage" :items="PER_PAGE_OPTIONS" />
    </div>
    <div class="mt-4 bg-white shadow-sm rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-zinc-300 text-sm">
        <thead class="bg-zinc-200 font-medium text-zinc-600 text-left align-bottom tracking-wider">
          <tr>
            <th scope="col" class="px-4 py-3">対象</th>
            <th scope="col" class="px-4 py-3">ファイル名</th>
            <th scope="col" class="px-4 py-3 text-center">ステータス</th>
            <th scope="col" class="px-4 py-3">DL済み</th>
            <th scope="col" class="px-4 py-3">作成日</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-300 text-zinc-600">
          <template v-if="files.length > 0">
            <tr v-for="file in files" :key="file.id">
              <td class="px-4 py-3">{{ file.subject }}</td>
              <td class="px-4 py-3">
                <button
                  v-if="file.status === '完了'"
                  type="button"
                  class="text-left cursor-pointer"
                  :class="file.downloadedAt ? '' : 'font-semibold underline'"
                  @click="downloadFile(file.id, file.filename)"
                >
                  {{ file.filename }}
                </button>
                <span v-else>{{ file.filename }}</span>
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  :class="statusClass(file.status)"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  >{{ file.status }}</span
                >
              </td>
              <td class="px-4 py-3">{{ file.downloadedAt ? "DL済み" : "" }}</td>
              <td class="px-4 py-3">{{ file.createdAt }}</td>
              <td class="px-4 py-3">
                <button-icon-danger @click="openDeleteModal(file)"><trash-icon class="size-4" /></button-icon-danger>
              </td>
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
    <pagination :links="links" :pagination="pagination" :per-page="filters.perPage" class="mt-4" />

    <dialog-modal
      v-model="showDeleteModal"
      title="ファイル削除"
      show-close
      dialog-class="max-w-sm w-full"
      @close="closeDialogModal"
    >
      <div class="mt-4">
        <p>該当のファイルを削除しますか？</p>
        <div class="mt-4 flex justify-center items-center gap-4">
          <button-secondary @click="closeDialogModal">キャンセル</button-secondary>
          <button-primary @click="confirmDelete">削除する</button-primary>
        </div>
      </div>
    </dialog-modal>
  </div>
</template>
