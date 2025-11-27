<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-theme-secondary mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      :required="required"
      :class="[
        'input-base',
        error ? 'border-red-500' : ''
      ]"
    >
      <option value="">{{ placeholder || 'Seleccione una opción' }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id?: string;
  label?: string;
  modelValue: string | number;
  options: { value: string | number; label: string }[];
  placeholder?: string;
  required?: boolean;
  error?: string;
}>();

defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>
