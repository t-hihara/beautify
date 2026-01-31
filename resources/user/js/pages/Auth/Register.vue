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
  lastNameKana: string;
  firstNameKana: string;
  email: string;
  password: string;
};

type CustomerForm = {
  name: string;
  nameKana: string;
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
const isSameNameKana = ref<boolean>(false);
const isSameEmail = ref<boolean>(false);
const agreedToTerms = ref<boolean>(false);

const form = useForm<Form>({
  user: {
    lastName: "",
    firstName: "",
    lastNameKana: "",
    firstNameKana: "",
    email: "",
    password: "",
  },
  customer: {
    name: "",
    nameKana: "",
    email: "",
    phone: "",
    dob: "",
    gender: "",
  },
});

const combinedUserName = computed<string>(() => [form.user.lastName, form.user.firstName].filter(Boolean).join(" "));
const combinedUserNameKana = computed<string>(() =>
  [form.user.lastNameKana, form.user.firstNameKana].filter(Boolean).join(" "),
);

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

watch(isSameNameKana, (checked) => {
  form.customer.nameKana = checked ? combinedUserNameKana.value : "";
});

watch(
  () => [form.user.lastNameKana, form.user.firstNameKana],
  () => {
    if (isSameNameKana.value) form.customer.nameKana = combinedUserNameKana.value;
  },
);

watch(isSameEmail, (checked) => {
  form.customer.email = checked ? form.user.email : "";
});

watch(
  () => form.user.email,
  () => {
    if (isSameEmail.value) form.customer.email = form.user.email;
  },
);
</script>

<template>
  <div class="sm:min-w-4xl sm:mx-auto">
    <Head title="会員登録" />
    <div class="hidden sm:block my-8">
      <Head title="新規会員登録" />
      <h2 class="text-3xl font-semibold">会員登録</h2>
      <form @submit.prevent="submit" class="mt-8">
        <dl class="grid grid-cols-2 rounded-md border border-rose-300">
          <div class="col-span-1 flex items-stretch">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
          <div class="col-span-1 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
              ユーザー姓(カナ)<span class="text-red-500">※</span>
            </dt>
            <dd class="flex-1 p-4">
              <form-text
                v-model="form.user.lastNameKana"
                field="user.lastNameKana"
                placeholder="ヤマダ"
                class="w-full"
                :error="form.errors"
              />
            </dd>
          </div>
          <div class="col-span-1 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
              ユーザー名(カナ)<span class="text-red-500">※</span>
            </dt>
            <dd class="flex-1 p-4">
              <form-text
                v-model="form.user.firstNameKana"
                field="user.firstNameKana"
                placeholder="タロウ"
                class="w-full"
                :error="form.errors"
              />
            </dd>
          </div>
          <div class="col-span-2 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
              電話番号<span class="text-red-500">※</span>
            </dt>
            <dd class="flex-1 p-4">
              <form-text
                v-model="form.customer.phone"
                field="customer.phone"
                placeholder="09012341234（ハイフンなし）"
                class="w-full"
                :error="form.errors"
              />
            </dd>
          </div>
          <div class="col-span-2 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
            <dt class="w-52 shrink-0 flex items-center p-4 bg-rose-200 text-zinc-800">サイト表示名</dt>
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
            <dt class="w-52 shrink-0 flex items-center p-4 bg-rose-200 text-zinc-800">サイト表示名カナ</dt>
            <dd class="flex-1 p-4">
              <form-text
                v-model="form.customer.nameKana"
                field="customer.nameKana"
                placeholder="タロウ"
                class="w-full"
                :error="form.errors"
              />
              <form-checkbox
                v-model="isSameNameKana"
                field="isSameNameKana"
                title="ユーザー名カナと同じにする"
                label-position="after"
                class="mt-2"
                :is-flex="true"
              />
            </dd>
          </div>
          <div class="col-span-2 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800 whitespace-nowrap">
              連絡用メールアドレス<span class="text-red-500">※</span>
            </dt>
            <dd class="flex-1 p-4">
              <form-text
                v-model="form.customer.email"
                field="customer.email"
                placeholder="example@email.com"
                class="w-full"
                :error="form.errors"
              />
              <form-checkbox
                v-model="isSameEmail"
                field="isSameEmail"
                title="メールアドレスと同じにする"
                label-position="after"
                class="mt-2"
                :is-flex="true"
              />
            </dd>
          </div>
          <div class="col-span-2 flex items-stretch border-t border-rose-300">
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
            <dt class="w-52 shrink-0 flex items-center gap-2 p-4 bg-rose-200 text-zinc-800">
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
          <button-submit class="w-1/4 mx-auto" :disabled="!agreedToTerms || form.processing"> 新規登録 </button-submit>
        </div>
      </form>
    </div>
    <div class="sm:hidden mb-4 flex flex-1 min-h-0 flex-col">
      <div class="bg-rose-400 px-4 py-3 text-center text-white font-medium">新規会員登録</div>
      <div class="stepper">
        <div class="stepper-step stepper-step-first">項目入力</div>
        <div class="stepper-step stepper-step-middle">登録メール確認</div>
        <div class="stepper-step stepper-step-last">完了</div>
      </div>
      <form @submit.prevent="submit">
        <div class="space-y-4">
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">ユーザー氏名<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-text
            v-model="form.user.lastName"
            field="user.lastName"
            placeholder="山田"
            class="w-full px-4"
            :error="form.errors"
          />
          <form-text
            v-model="form.user.firstName"
            field="user.firstName"
            placeholder="太郎"
            class="w-full px-4"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">ユーザー氏名(カナ)<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-text
            v-model="form.user.lastNameKana"
            field="user.lastNameKana"
            placeholder="ヤマダ"
            class="w-full px-4"
            :error="form.errors"
          />
          <form-text
            v-model="form.user.firstNameKana"
            field="user.firstNameKana"
            placeholder="タロウ"
            class="w-full px-4"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">電話番号<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-text
            v-model="form.customer.phone"
            field="customer.phone"
            placeholder="09012341234（ハイフンなし）"
            class="w-full px-4"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">メールアドレス<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-email
            v-model="form.user.email"
            field="user.email"
            placeholder="メールアドレス"
            class="w-full px-4"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">パスワード<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-password
            v-model="form.user.password"
            field="user.password"
            placeholder="パスワード"
            class="w-full px-4"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">サイト表示名</p>
          </div>
          <form-text
            v-model="form.customer.name"
            field="customer.name"
            placeholder="太郎"
            class="w-full px-4"
            :error="form.errors"
          />
          <form-checkbox
            v-model="isSameName"
            field="isSameName"
            title="ユーザー名と同じにする"
            label-position="after"
            class="mt-2 px-4"
            :is-flex="true"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">サイト表示名(カナ)</p>
          </div>
          <form-text
            v-model="form.customer.nameKana"
            field="customer.nameKana"
            placeholder="タロウ"
            class="w-full px-4"
            :error="form.errors"
          />
          <form-checkbox
            v-model="isSameNameKana"
            field="isSameNameKana"
            title="ユーザー名(カナ)と同じにする"
            label-position="after"
            class="mt-2 px-4"
            :is-flex="true"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">連絡用メールアドレス<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-text
            v-model="form.customer.email"
            field="customer.email"
            placeholder="example@email.com"
            class="w-full px-4"
            :error="form.errors"
          />
          <form-checkbox
            v-model="isSameEmail"
            field="isSameName"
            title="メールアドレスと同じにする"
            label-position="after"
            class="mt-2 px-4"
            :is-flex="true"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">生年月日<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-date-time
            v-model="form.customer.dob"
            field="customer.dob"
            class="w-full px-4"
            :error="form.errors"
            :min-date="null"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">性別<span class="ml-2 text-red-500">※</span></p>
          </div>
          <form-single-select
            v-model="form.customer.gender"
            field="customer.gender"
            class="w-full px-4"
            :items="genders"
            :error="form.errors"
          />
          <div class="p-2 bg-zinc-100">
            <p class="text-sm">利用規約<span class="ml-2 text-red-500">※</span></p>
          </div>
          <div class="mt-6 mx-4 rounded-md border border-rose-300 bg-rose-50/50 p-4">
            <p class="text-xs text-zinc-700">
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
              class="text-xs mt-4"
              :is-flex="true"
              :required="true"
            />
          </div>
        </div>
        <div class="mt-4 w-full">
          <button-submit class="w-1/4 mx-auto" :disabled="!agreedToTerms || form.processing"> 新規登録 </button-submit>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.stepper {
  display: flex;
  align-items: stretch;
  min-height: 2.75rem;
  background: #e4e4e7;
}

.stepper-step {
  position: relative;
  flex: 1 1 0;
  min-width: 4rem;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 0.25rem;
  font-size: 0.8125rem;
  line-height: 1.25;
}

.stepper-step-first {
  z-index: 3;
  background: #fff;
  color: #27272a;
  font-weight: 500;
  clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%);
  margin-right: -12px;
}

.stepper-step-middle {
  z-index: 2;
  background: #e4e4e7;
  color: #52525b;
  clip-path: polygon(12px 50%, 0 0, 0 100%, calc(100% - 12px) 100%, 100% 50%, calc(100% - 12px) 0);
  margin-left: -12px;
  margin-right: -12px;
}

.stepper-step-last {
  z-index: 1;
  background: #e4e4e7;
  color: #52525b;
  clip-path: polygon(12px 50%, 0 0, 0 100%, 100% 100%, 100% 0);
  margin-left: -12px;
}
</style>
