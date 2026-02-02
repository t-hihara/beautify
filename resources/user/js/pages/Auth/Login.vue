<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { route } from "ziggy-js";

import GoogleLogo from "@/assets/images/Logo/google-icon.svg";
import FormEmail from "@/common/js/components/Form/FormEmail.vue";
import FormPassword from "@/common/js/components/Form/FormPassword.vue";
import { ButtonLink, ButtonSubmit, TextLink } from "@/common/js/components/Ui/ButtonIndex";

defineOptions({
  layoutProps: { centerMain: true },
});

type LoginForm = {
  email: string;
  password: string;
};

type Tab = {
  id: "login" | "register";
  label: "ログイン" | "会員登録";
};

const page = usePage();
const tabs = ref<Tab[]>([
  { id: "login", label: "ログイン" },
  { id: "register", label: "会員登録" },
]);
const activeTab = ref<"login" | "register">("login");

const form = useForm<LoginForm>({
  email: "",
  password: "",
});

const errors = computed<Record<string, string>>(() => ({
  ...(page.props.errors as Record<string, string>),
  ...form.errors,
}));

const submit = (): void => {
  form.post(route("user.login"));
};
</script>

<template>
  <Head title="ログイン" />
  <div class="hidden sm:flex justify-center items-center">
    <div class="max-w-md w-full bg-zinc-100 rounded-md shadow-xl p-8">
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl font-bold text-zinc-800">ログイン</h2>
        </div>
        <form @submit.prevent="submit">
          <form-email
            v-model="form.email"
            title="メールアドレス"
            field="email"
            placeholder="example@email.com"
            required
            class="flex flex-col gap-1"
            :error="errors"
          />
          <form-password
            v-model="form.password"
            title="パスワード"
            field="password"
            placeholder="password"
            required
            class="mt-4 flex flex-col gap-1"
            :error="errors"
          />
          <p class="mt-4 text-end text-sm text-zinc-600">
            <text-link :href="route('user.password.request')">パスワードを忘れた方</text-link>
          </p>
          <div class="mt-8 w-2/3 mx-auto">
            <button-submit class="w-full">ログイン</button-submit>
          </div>
          <p class="mt-4 text-center text-sm text-zinc-600">
            アカウントをお持ちでない方は
            <text-link :href="route('user.create')">新規登録</text-link>
          </p>
        </form>
        <div>
          <div class="flex items-center gap-4">
            <hr class="text-zinc-400 w-full" />
            <span>or</span>
            <hr class="text-zinc-400 w-full" />
          </div>
          <div class="mt-4 w-2/3 mx-auto rounded-full overflow-hidden">
            <button-link
              :href="route('user.login.google')"
              variant="filled"
              class="flex justify-center items-center gap-2 rounded-full"
            >
              <google-logo class="size-5" />
              Googleでログイン
            </button-link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="sm:hidden">
    <div class="flex border-b border-zinc-200">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        type="button"
        class="flex-1 py-3 text-sm font-medium border-b-2 transition-colors"
        :class="activeTab === tab.id ? 'border-rose-500 text-rose-500' : 'border-transparent text-zinc-600'"
        @click="activeTab = tab.id"
      >
        {{ tab.label }}
      </button>
    </div>
    <div v-show="activeTab === 'login'" class="px-4 py-6">
      <form @submit.prevent="submit">
        <form-email
          v-model="form.email"
          title="メールアドレス"
          field="email"
          placeholder="example@email.com"
          required
          class="flex flex-col gap-1"
          :error="errors"
        />
        <form-password
          v-model="form.password"
          title="パスワード"
          field="password"
          placeholder="password"
          required
          class="mt-4 flex flex-col gap-1"
          :error="errors"
        />
        <div class="mt-4">
          <text-link :href="route('user.password.request')" class="block text-xs text-end text-zinc-600"
            >パスワードを忘れた方</text-link
          >
          <button-submit class="w-full mt-4">ログイン</button-submit>
        </div>
      </form>
      <div class="mt-4">
        <div class="flex items-center gap-4">
          <hr class="text-zinc-400 w-full" />
          <span>or</span>
          <hr class="text-zinc-400 w-full" />
        </div>
        <div class="mt-4 w-2/3 mx-auto rounded-full overflow-hidden">
          <button-link
            :href="route('user.login.google')"
            variant="filled"
            class="flex justify-center items-center gap-2 rounded-full"
          >
            <google-logo class="size-5" />
            Googleでログイン
          </button-link>
        </div>
      </div>
      <div class="mt-12 py-4 w-full text-center border-t border-zinc-200">
        <h2 class="text-xl font-semibold">初めてご利用の方</h2>
        <div class="mt-2 w-full">
          <button-link
            :href="route('user.create')"
            variant="filled"
            class="flex justify-center items-center gap-2 rounded-full"
            >新規登録</button-link
          >
        </div>
      </div>
    </div>
    <div v-show="activeTab === 'register'" class="px-4 py-6 space-y-4">
      <div class="w-full text-center">
        <h2 class="text-xl font-semibold">初めてご利用の方</h2>
        <div class="mt-2 w-full space-y-4">
          <button-link
            :href="route('user.create')"
            variant="filled"
            class="flex justify-center items-center gap-2 rounded-full"
            >メールアドレスで登録</button-link
          >
          <button-link
            :href="route('user.login.google')"
            variant="filled"
            class="flex justify-center items-center gap-2 rounded-full"
          >
            <google-logo class="size-5" />
            Googleでサインイン
          </button-link>
          <p class="text-xs leading-5">
            会員登録に伴い、<text-link href="/">beauty会員登録</text-link>および<text-link href="/"
              >個人情報の取り扱い</text-link
            >について同意したものとみなします。
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
