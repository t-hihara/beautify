<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { route } from "ziggy-js";

import FormPassword from "@/common/js/components/Form/FormPassword.vue";
import { ButtonSubmit, TextLink } from "@/common/js/components/Ui/ButtonIndex";

defineOptions({
  layoutProps: { centerMain: true },
});

defineProps<{
  token: string;
  email: string | null;
}>();

type ResetPasswordForm = {
  token: string;
  email: string;
  password: string;
  passwordConfirmation: string;
};

const page = usePage();
const form = useForm<ResetPasswordForm>({
  token: page.props.token as string,
  email: (page.props.email as string) ?? "",
  password: "",
  passwordConfirmation: "",
});

const errors = computed<Record<string, string>>(() => ({
  ...(page.props.errors as Record<string, string>),
  ...form.errors,
}));

const submit = (): void => {
  form.post(route("user.password.update"));
};
</script>

<template>
  <Head title="パスワードの再設定" />
  <div class="hidden sm:flex justify-center items-center">
    <div class="max-w-md w-full bg-zinc-100 rounded-md shadow-xl p-8">
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl font-bold text-zinc-800">パスワードの再設定</h2>
          <p class="mt-2 text-sm text-zinc-600">
            新しいパスワードを入力してください。
          </p>
        </div>
        <form @submit.prevent="submit">
          <form-password
            v-model="form.password"
            title="新しいパスワード"
            field="password"
            placeholder=""
            required
            class="flex flex-col gap-1"
            :error="errors"
          />
          <form-password
            v-model="form.passwordConfirmation"
            title="新しいパスワード（確認）"
            field="password_confirmation"
            placeholder=""
            required
            class="mt-4 flex flex-col gap-1"
            :error="errors"
          />
          <div class="mt-8 w-2/3 mx-auto">
            <button-submit class="w-full">再設定する</button-submit>
          </div>
          <p class="mt-4 text-center text-sm text-zinc-600">
            <text-link :href="route('user.loginForm')">ログイン画面へ</text-link>
          </p>
        </form>
      </div>
    </div>
  </div>
  <div class="sm:hidden px-4 py-6">
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold text-zinc-800">パスワードの再設定</h2>
      <p class="mt-2 text-sm text-zinc-600">
        新しいパスワードを入力してください。
      </p>
    </div>
    <form @submit.prevent="submit">
      <form-password
        v-model="form.password"
        title="新しいパスワード"
        field="password"
        placeholder=""
        required
        class="flex flex-col gap-1"
        :error="errors"
      />
      <form-password
        v-model="form.passwordConfirmation"
        title="新しいパスワード（確認）"
        field="password_confirmation"
        placeholder=""
        required
        class="mt-4 flex flex-col gap-1"
        :error="errors"
      />
      <div class="mt-8">
        <button-submit class="w-full">再設定する</button-submit>
      </div>
      <p class="mt-4 text-center text-sm text-zinc-600">
        <text-link :href="route('user.loginForm')">ログイン画面へ</text-link>
      </p>
    </form>
  </div>
</template>
