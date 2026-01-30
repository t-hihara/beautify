<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";

import { FormEmail, FormPassword, FormText } from "@common/components/Form/FormIndex";
import { ButtonSubmit } from "@/common/js/components/Ui/ButtonIndex";
import { route } from "ziggy-js";

type LoginForm = {
  lastName: string;
  firstName: string;
  email: string;
  password: string;
};

const form = useForm<LoginForm>({
  lastName: "",
  firstName: "",
  email: "",
  password: "",
});

const submit = (): void => {
  form.post(route("user.store"));
};
</script>

<template>
  <div class="min-w-4xl py-8 mx-auto">
    <Head title="新規会員登録" />
    <h2 class="text-3xl font-semibold">会員登録</h2>
    <div class="mt-6 p-8 rounded-lg bg-zinc-100">
      <form @submit.prevent="submit">
        <div class="grid grid-cols-2 gap-4 space-y-4">
          <form-text
            v-model="form.lastName"
            title="姓"
            field="lastName"
            placeholder="山田"
            required
            class="flex flex-col gap-1"
            :error="form.errors"
          />
          <form-text
            v-model="form.firstName"
            title="名"
            field="firstName"
            placeholder="太郎"
            required
            class="flex flex-col gap-1"
            :error="form.errors"
          />
          <form-email
            v-model="form.email"
            title="メールアドレス"
            field="email"
            placeholder="example@email.com"
            required
            class="flex flex-col gap-1"
            :error="form.errors"
          />
          <form-password
            v-model="form.password"
            title="パスワード"
            field="password"
            placeholder="password"
            required
            class="flex flex-col gap-1"
            :error="form.errors"
          />
        </div>
        <div class="mt-4 w-full">
          <button-submit class="w-1/4 mx-auto">新規登録</button-submit>
        </div>
      </form>
    </div>
  </div>
</template>
