<template>
  <q-btn color="red-6" icon="logout" label="Sair" :loading="isLoggingOut" :disable="isLoggingOut"
    @click="onClickLogout" />
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

defineProps({
  isLoggingOut: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits<{
  (e: 'onClickLogout'): void;
}>();

const onClickLogout = () => {
  emit('onClickLogout');
};
</script>
