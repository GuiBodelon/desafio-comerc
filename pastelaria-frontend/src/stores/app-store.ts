import { defineStore, acceptHMRUpdate } from 'pinia'

export const useAppStore = defineStore('App', {
  state: () => {
    return {
      dark: false,
    }
  },
  getters: {
    isDarkActive: (state) => {
      return state.dark
    },
  },
  actions: {
    setDarkMode(val: boolean) {
      this.dark = val
    },
  },
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useAppStore, import.meta.hot))
}
