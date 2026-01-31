<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { route } from "ziggy-js";
import type { Enum } from "@/common/js/lib";

import {
  FormCheckbox,
  FormDateTime,
  FormEmail,
  FormPassword,
  FormSingleSelect,
  FormText,
} from "@common/components/Form/FormIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";

type UserForm = {
  lastName: string;
  firstName: string;
  email: string;
  password: string;
};

type CustomerForm = {
  name: string;
  nameKana: string | null;
  email: string;
  phone: string;
  dob: string;
  gender: string;
};

type Form = {
  user: UserForm;
  customer: CustomerForm;
};

defineProps<{
  genders: Enum[];
}>();

const isSameName = ref<boolean>(false);
const agreedToTerms = ref<boolean>(false);

const form = useForm<Form>({
  user: {
    lastName: "",
    firstName: "",
    email: "",
    password: "",
  },
  customer: {
    name: "",
    nameKana: null,
    email: "",
    phone: "",
    dob: "",
    gender: "",
  },
});

const combinedUserName = computed<string>(() => [form.user.lastName, form.user.firstName].filter(Boolean).join(" "));

const submit = (): void => {
  form.post(route("user.store"));
};

watch(isSameName, (checked) => {
  form.customer.name = checked ? combinedUserName.value : "";
});

watch(
  () => [form.user.lastName, form.user.firstName],
  () => {
    if (isSameName.value) form.customer.name = combinedUserName.value;
  },
);
</script>

<template>
  <div class="hidden sm:block min-w-4xl py-8 mx-auto">
    <Head title="新規会員登録" />
    <h2 class="text-3xl font-semibold">会員登録</h2>
    <form @submit.prevent="submit" class="mt-8">
      <dl class="grid grid-cols-2 rounded-md border border-rose-300">
        <div class="col-span-1 flex items-stretch">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            ユーザー姓<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-text
              v-model="form.user.lastName"
              field="user.lastName"
              placeholder="山田"
              class="w-full"
              :error="form.errors"
            />
          </dd>
        </div>
        <div class="col-span-1 flex items-stretch">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            ユーザー名<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-text
              v-model="form.user.firstName"
              field="user.firstName"
              placeholder="太郎"
              class="w-full"
              :error="form.errors"
            />
          </dd>
        </div>
        <div class="col-span-2 flex items-stretch border-t border-rose-300">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            メールアドレス<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-email
              v-model="form.user.email"
              field="user.email"
              placeholder="example@email.com"
              class="w-full"
              :error="form.errors"
            />
          </dd>
        </div>
        <div class="col-span-2 flex items-stretch border-t border-rose-300">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            パスワード<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-password
              v-model="form.user.password"
              field="user.password"
              placeholder="password"
              class="w-full"
              :error="form.errors"
            />
          </dd>
        </div>
        <div class="col-span-2 flex items-stretch border-t border-rose-300">
          <dt class="w-40 shrink-0 flex items-center p-4 bg-rose-200 text-zinc-800">サイト表示名</dt>
          <dd class="flex-1 p-4">
            <form-text
              v-model="form.customer.name"
              field="customer.name"
              placeholder="太郎"
              class="w-full"
              :error="form.errors"
            />
            <form-checkbox
              v-model="isSameName"
              field="isSameName"
              title="ユーザー名と同じにする"
              label-position="after"
              class="mt-2"
              :is-flex="true"
            />
          </dd>
        </div>
        <div class="col-span-2 flex items-stretch border-t border-rose-300">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            生年月日<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-date-time
              v-model="form.customer.dob"
              field="customer.dob"
              class="w-full"
              :error="form.errors"
              :min-date="null"
            />
          </dd>
        </div>
        <div class="col-span-2 flex items-stretch border-t border-rose-300">
          <dt class="w-40 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
            性別<span class="text-red-500">※</span>
          </dt>
          <dd class="flex-1 p-4">
            <form-single-select
              v-model="form.customer.gender"
              field="customer.gender"
              class="w-full"
              :items="genders"
              :error="form.errors"
            />
          </dd>
        </div>
      </dl>
      <div class="mt-6 rounded-md border border-rose-300 bg-rose-50/50 p-4">
        <p class="mb-3 text-sm text-zinc-700">
          会員登録にあたり、
          <a href="#" class="text-rose-600 underline hover:text-rose-700">利用規約</a>
          および
          <a href="#" class="text-rose-600 underline hover:text-rose-700">プライバシーポリシー</a>
          をご確認のうえ、同意の上ご登録ください。
        </p>
        <form-checkbox
          v-model="agreedToTerms"
          field="agreedToTerms"
          title="利用規約およびプライバシーポリシーに同意する"
          label-position="after"
          :is-flex="true"
          :required="true"
        />
      </div>
      <div class="mt-4 w-full">
        <button-submit class="w-1/4 mx-auto" :disabled="!agreedToTerms"> 新規登録 </button-submit>
      </div>
    </form>
  </div>
</template>
