<template>
  <button :type="type" :disabled="disabled" :class="buttonClasses" @click="$emit('click', $event)">
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
}>();

defineEmits<{
  click: [event: MouseEvent];
}>();

const buttonClasses = computed(() => {
  const base = 'btn';
  const variant = `btn-${props.variant || 'primary'}`;
  const size = props.size ? `btn-${props.size}` : '';
  
  return [base, variant, size].filter(Boolean).join(' ');
});
</script>
