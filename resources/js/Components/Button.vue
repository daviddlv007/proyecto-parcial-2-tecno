<template>
  <button 
    :type="type"
    :class="buttonClasses"
    :disabled="disabled"
    @click="$emit('click', $event)"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
  variant?: 'primary' | 'secondary' | 'danger' | 'success';
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
}>(), {
  variant: 'primary',
  type: 'button',
  disabled: false
});

const emit = defineEmits<{
  click: [event: MouseEvent]
}>();

const buttonClasses = computed(() => {
  const base = 'px-4 py-2 rounded-lg transition-colors duration-200 disabled:opacity-50';
  const variants = {
    primary: 'button-primary',
    secondary: 'button-secondary',
    danger: 'button-danger',
    success: 'button-success'
  };
  return `${base} ${variants[props.variant]}`;
});
</script>
