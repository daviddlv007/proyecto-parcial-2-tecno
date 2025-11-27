<template>
  <div class="mt-4 flex justify-between items-center">
    <div class="text-sm text-gray-700 dark:text-gray-300">
      Mostrando {{ data.from }} a {{ data.to }} de {{ data.total }} registros
    </div>
    <div class="flex gap-2">
      <Link
        v-for="link in data.links"
        :key="link.label"
        :href="link.url || '#'"
        :class="{
          'bg-blue-600 text-white': link.active,
          'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed': !link.url,
          'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600': link.url && !link.active,
        }"
        class="px-3 py-1 rounded border dark:border-gray-600"
        v-html="link.label"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  data: {
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();
</script>
