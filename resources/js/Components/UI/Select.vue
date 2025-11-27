<template>
  <div class="form-group">
    <label v-if="label" :for="id" :class="['form-label', { 'form-label-required': required }]">
      {{ label }}
    </label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      :required="required"
      :disabled="disabled"
      :class="['select', { 'input-error': error }]"
    >
      <option value="" disabled>{{ placeholder || 'Seleccionar...' }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="form-error">{{ error }}</p>
    <p v-if="hint && !error" class="form-hint">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id?: string;
  label?: string;
  modelValue: string | number;
  options: Array<{ value: string | number; label: string }>;
  placeholder?: string;
  required?: boolean;
  disabled?: boolean;
  error?: string;
  hint?: string;
}>();

defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>
