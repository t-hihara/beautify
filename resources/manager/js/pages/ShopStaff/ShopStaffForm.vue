<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useGuard } from "@manager/composables/useGuard";
import {
  FormText,
  FormNumber,
  FormEmail,
  FormSingleSelect,
  FormTextarea,
  FormSingleImage,
  FormSwitchToggle,
} from "@/common/js/components/Form/FormIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";
import type { EnumType } from "@/common/js/lib";

type StaffType = {
  id: number;
  lastName: string;
  firstName: string;
  email: string;
  position: string;
  description: string | null;
  experienceYears: number;
  activeFlag: string;
  image: ImageType | null;
};

type ImageType = {
  id: number;
  fileName: string;
  filePath: string;
};

type FormType = {
  _method: "POST" | "PATCH";
  id?: number;
  lastName: string;
  firstName: string;
  email: string;
  position: string;
  description: string | null;
  experienceYears: number;
  activeFlag: string;
  image: File | string | null;
};

const { staff } = defineProps<{
  staff?: StaffType;
  activeFlags: EnumType[];
  positions: EnumType[];
}>();

const guard = useGuard();
const isEdit = computed<boolean>(() => route().current() === `${guard.value}.staffs.edit`);

const form = useForm<FormType>({
  _method: "POST",
  id: staff?.id ?? undefined,
  lastName: staff?.lastName ?? "",
  firstName: staff?.firstName ?? "",
  email: staff?.email ?? "",
  position: staff?.position ?? "",
  description: staff?.description ?? (null as string | null),
  experienceYears: staff?.experienceYears ?? 0,
  activeFlag: staff?.activeFlag ?? "active",
  image: (staff?.image?.filePath ?? null) as File | string | null,
});

const activeFlag = computed<boolean>({
  get: () => form.activeFlag === "active",
  set: (v: boolean) => {
    form.activeFlag = v ? "active" : "inactive";
  },
});

const submit = (): void => {
  if (isEdit.value) {
    form._method = "PATCH";
    form.post(route(`${guard.value}.staffs.update`, staff?.id));
  } else {
    //
  }
};
</script>

<template>
  <div>
    <Head :title="isEdit ? '店舗スタッフ編集' : '店舗スタッフ登録'" />
    <div>
      <h2 class="text-3xl">{{ isEdit ? "店舗スタッフ編集" : "店舗スタッフ登録" }}</h2>
    </div>
    <div class="mt-6">
      <form @submit.prevent="submit">
        <div class="px-3 py-1 rounded-md bg-zinc-200">
          <p class="text-sm font-semibold text-zinc-800">スタッフ情報</p>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-6">
          <form-text
            v-model="form.lastName"
            title="スタッフ名(姓)"
            field="lastName"
            placeholder="スタッフ名(姓)"
            required
            :error="form.errors"
          />
          <form-text
            v-model="form.firstName"
            title="スタッフ名(名)"
            field="firstName"
            placeholder="スタッフ名(名)"
            required
            :error="form.errors"
          />
          <form-email
            v-model="form.email"
            title="メールアドレス"
            field="email"
            placeholder="メールアドレス"
            required
            :error="form.errors"
          />
          <form-single-select
            v-model="form.position"
            title="ポジション"
            field="position"
            required
            :items="positions"
            :error="form.errors"
          />
          <form-number
            v-model="form.experienceYears"
            title="経歴年数"
            field="experienceYears"
            placeholder="0"
            :error="form.errors"
          />
          <form-switch-toggle v-model="activeFlag" title="有効状態" field="activeFlag" required :error="form.errors" />
          <form-single-image v-model="form.image" field="image" title="プロフィール画像" :error="form.errors" />
          <form-textarea
            v-model="form.description"
            title="説明"
            field="description"
            placeholder="説明"
            class="col-span-2"
            :error="form.errors"
          />
        </div>
        <button-submit :disabled="form.processing">{{ isEdit ? "編集する" : "登録する" }}</button-submit>
      </form>
    </div>
  </div>
</template>
