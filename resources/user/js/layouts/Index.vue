<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import Header from '@user/components/Layout/Header.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAuth = computed(() => !!user.value);
</script>

<template>
  <div>
    <Header :is-auth="isAuth" :user="user" />
    <main>
      <slot></slot>
    </main>
  </div>
</template>
