<template>
  <span :class="badgeClass">
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  variant?: 'success' | 'warning' | 'error' | 'info' | 'neutral';
}>();

const badgeClass = computed(() => {
  return `badge-${props.variant || 'neutral'}`;
});
</script>
