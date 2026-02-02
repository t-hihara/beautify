<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { route } from "ziggy-js";

import FormEmail from "@/common/js/components/Form/FormEmail.vue";
import { ButtonSubmit, TextLink } from "@/common/js/components/Ui/ButtonIndex";

defineOptions({
  layoutProps: { centerMain: true },
});

type ForgotPasswordForm = {
  email: string;
};

const page = usePage();
const form = useForm<ForgotPasswordForm>({
  email: "",
});

const errors = computed<Record<string, string>>(() => ({
  ...(page.props.errors as Record<string, string>),
  ...form.errors,
}));

const submit = (): void => {
  form.post(route("user.password.email"));
};
</script>

<template>
  <Head title="パスワードをお忘れの方" />
  <div class="hidden sm:flex justify-center items-center">
    <div class="max-w-md w-full bg-zinc-100 rounded-md shadow-xl p-8">
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl font-bold text-zinc-800">パスワードをお忘れの方</h2>
          <p class="mt-2 text-sm text-zinc-600">
            登録済みのメールアドレスを入力してください。<br />
            パスワード再設定用のリンクをお送りします。
          </p>
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
          <div class="mt-8 w-2/3 mx-auto">
            <button-submit class="w-full">送信する</button-submit>
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
      <h2 class="text-xl font-bold text-zinc-800">パスワードをお忘れの方</h2>
      <p class="mt-2 text-sm text-zinc-600">
        登録済みのメールアドレスを入力してください。<br />
        パスワード再設定用のリンクをお送りします。
      </p>
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
      <div class="mt-8">
        <button-submit class="w-full">送信する</button-submit>
      </div>
      <p class="mt-4 text-center text-sm text-zinc-600">
        <text-link :href="route('user.loginForm')">ログイン画面へ</text-link>
      </p>
    </form>
  </div>
</template>
