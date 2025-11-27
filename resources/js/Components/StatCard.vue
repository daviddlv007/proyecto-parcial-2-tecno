<template>
  <div class="card card-hover p-6">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-theme-secondary">{{ title }}</p>
        <p class="mt-2 text-3xl font-bold text-theme-primary">{{ value }}</p>
        <p v-if="change" class="mt-2 flex items-center text-sm">
          <span :class="changeColor">
            {{ change }}
          </span>
          <span class="ml-2 text-theme-secondary">{{ changeLabel }}</span>
        </p>
      </div>
      <div :class="iconBgColor" class="rounded-full p-3">
        <slot name="icon">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
  change: {
    type: String,
    default: null,
  },
  changeLabel: {
    type: String,
    default: 'vs mes anterior',
  },
  color: {
    type: String,
    default: 'blue',
  },
  trend: {
    type: String,
    default: 'up',
    validator: (value) => ['up', 'down', 'neutral'].includes(value),
  },
});

const iconBgColor = computed(() => {
  const colors = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    purple: 'bg-purple-500',
    orange: 'bg-orange-500',
    red: 'bg-red-500',
    indigo: 'bg-indigo-500',
  };
  return colors[props.color] || colors.blue;
});

const changeColor = computed(() => {
  if (props.trend === 'up') return 'text-green-600 font-medium';
  if (props.trend === 'down') return 'text-red-600 font-medium';
  return 'text-gray-600 font-medium';
});
</script>
