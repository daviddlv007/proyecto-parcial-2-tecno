<template>
  <div class="flex items-center gap-2">
    <!-- Base theme selector dropdown -->
    <div class="hidden sm:block relative">
      <select
        :value="baseTheme"
        @change="(e) => setBaseTheme((e.target as HTMLSelectElement).value as 'kids' | 'youth' | 'adult')"
        class="px-3 py-1.5 pr-8 rounded-md text-sm border border-[rgb(var(--border-primary))] bg-[rgb(var(--surface))] text-[rgb(var(--text-primary))] cursor-pointer hover:border-[rgb(var(--brand-primary))] focus:outline-none focus:ring-2 focus:ring-[rgb(var(--brand-primary))] focus:ring-opacity-50 transition-colors"
      >
        <option v-for="t in themes" :key="t.value" :value="t.value">
          {{ t.label }}
        </option>
      </select>
    </div>

    <!-- Font size controls -->
    <div class="flex items-center gap-1">
      <button @click="decFont" class="p-1 rounded hover:bg-theme-hover" title="Reducir tamaño de fuente">A-</button>
      <button @click="incFont" class="p-1 rounded hover:bg-theme-hover" title="Aumentar tamaño de fuente">A+</button>
    </div>

    <!-- High contrast -->
    <button @click="toggleContrast" class="p-2 rounded hover:bg-theme-hover" :title="highContrast ? 'Desactivar alto contraste' : 'Activar alto contraste'">
      <svg v-if="!highContrast" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8-9h-1M4 12H3" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
      </svg>
    </button>

    <!-- Mode toggle (delegar en ThemeToggle visual) -->
    <ThemeToggle />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { useTheme } from '@/composables/useTheme';

const { baseTheme: baseRef, setBaseTheme, increaseFont, decreaseFont, highContrast: highContrastRef, setHighContrast } = useTheme();

const themes = [
  { label: 'Niños', value: 'kids' },
  { label: 'Jóvenes', value: 'youth' },
  { label: 'Adultos', value: 'adult' }
];

const baseTheme = computed(() => baseRef.value);
const highContrast = computed(() => highContrastRef.value);

function toggleContrast() {
  setHighContrast(!highContrast.value);
}

function incFont() {
  increaseFont(1);
}

function decFont() {
  decreaseFont(1);
}

// typescript helpers
/* eslint-disable @typescript-eslint/no-unused-vars */
const _x = setBaseTheme;
/* eslint-enable @typescript-eslint/no-unused-vars */
</script>

<style scoped>
.theme-button-active {
  box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
</style>
