<template>
  <div v-if="show" class="modal-overlay" @click.self="close">
    <div class="modal" @click.stop>
      <div v-if="title || $slots.header" class="modal-header">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-bold text-theme-primary">
            <slot name="header">{{ title }}</slot>
          </h3>
          <button @click="close" class="btn-ghost btn-sm p-2 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
      
      <div class="modal-body">
        <slot />
      </div>
      
      <div v-if="$slots.footer" class="modal-footer">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  show: boolean;
  title?: string;
}>();

const emit = defineEmits<{
  close: [];
}>();

const close = () => {
  emit('close');
};
</script>
