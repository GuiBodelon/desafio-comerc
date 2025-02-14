<template>
  <q-drawer :model-value="modelValue" side="left" overlay behavior="desktop" bordered
    @update:model-value="onUpdateModelValue">
    <q-list>
      <!-- Link para a página de login (ou dashboard, se autenticado) -->
      <q-item to="/" clickable>
        <q-item-section>
          <q-item-label>Início</q-item-label>
        </q-item-section>
      </q-item>

      <!-- Link para a página de clientes -->
      <q-item to="/clientes" clickable>
        <q-item-section>
          <q-item-label>Gerenciar Clientes</q-item-label>
        </q-item-section>
      </q-item>

      <!-- Link para a página de produtos (futuramente) -->
      <q-item to="/produtos" clickable>
        <q-item-section>
          <q-item-label>Gerenciar Produtos</q-item-label>
        </q-item-section>
      </q-item>

      <!-- Link para a página de pedidos (futuramente) -->
      <q-item to="/pedidos" clickable>
        <q-item-section>
          <q-item-label>Gerenciar Pedidos</q-item-label>
        </q-item-section>
      </q-item>

      <!-- Link para a página de relatórios (futuramente) -->
      <q-item to="/relatorios" clickable>
        <q-item-section>
          <q-item-label>Relatórios</q-item-label>
        </q-item-section>
      </q-item>

    </q-list>
  </q-drawer>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits<{
  (e: 'update:model-value', value: boolean): void;
}>();

const onUpdateModelValue = (value: boolean) => {
  emit('update:model-value', value);
};
</script>
