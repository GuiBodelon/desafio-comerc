<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <router-view v-slot="{ Component }">
        <transition name="scale-app" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'MainLayout',

  components: {},

  setup() {
    return {
      //
    };
  },
});
</script>
<style scoped>
/*ROUTER ANIMATED TRANSITION*/
.scale-app-enter-active,
.scale-app-leave-active {
  transition: all 0.3s ease;
}

.scale-app-enter-from,
.scale-app-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
