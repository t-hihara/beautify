<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";

import { ButtonLink, ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";
import GoogleLogo from "@/assets/images/Logo/google-icon.svg";
import FormEmail from "@/common/js/components/Form/FormEmail.vue";
import FormPassword from "@/common/js/components/Form/FormPassword.vue";
import { computed } from "vue";

type LoginForm = {
  email: string;
  password: string;
};

const page = usePage();

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
  <div class="min-h-[calc(100vh-4rem)] flex justify-center items-center">
    <div class="max-w-md w-full bg-zinc-200 rounded-md shadow-xl p-8">
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl font-bold text-zinc-800">ログイン</h2>
        </div>
        <form @submit.prevent="submit" class="space-y-5">
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
            class="flex flex-col gap-1"
            :error="errors"
          />
          <div class="w-2/3 mx-auto">
            <button-submit class="w-full">ログイン</button-submit>
          </div>
        </form>

        <div class="mt-10">
          <div class="text-center">
            <h2 class="text-2xl font-bold text-zinc-800">他サイトIDでログイン</h2>
          </div>
          <div class="mt-4 w-2/3 mx-auto rounded-full overflow-hidden">
            <button-link
              :href="route('user.login.google')"
              variant="filled"
              class="flex justify-center items-center gap-2"
            >
              <google-logo class="size-5" />
              Googleでログイン
            </button-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
