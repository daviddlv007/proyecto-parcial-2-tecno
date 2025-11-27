<template>
  <div :class="containerClass">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
  variant?: 'default' | 'hover' | 'elevated';
  padding?: boolean;
}>(), {
  padding: true  // Por defecto, las cards TIENEN padding
});

const containerClass = computed(() => {
  const base = props.variant === 'hover' ? 'card-hover' : props.variant === 'elevated' ? 'card-elevated' : 'card';
  
  // Solo aplicar !p-0 si padding es explícitamente false
  const noPaddingClass = props.padding === false ? '!p-0' : '';
  
  return [base, noPaddingClass].filter(Boolean).join(' ');
});
</script>
