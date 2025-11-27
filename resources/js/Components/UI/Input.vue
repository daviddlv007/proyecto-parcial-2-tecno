<template>
  <div class="form-group">
    <label v-if="label" :for="id" :class="['form-label', { 'form-label-required': required }]">
      {{ label }}
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :autocomplete="autocomplete"
      :class="['input', { 'input-error': error }]"
    />
    <p v-if="error" class="form-error">{{ error }}</p>
    <p v-if="hint && !error" class="form-hint">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id?: string;
  label?: string;
  type?: string;
  modelValue: string | number;
  placeholder?: string;
  required?: boolean;
  disabled?: boolean;
  error?: string;
  hint?: string;
  autocomplete?: string;
}>();

defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>
