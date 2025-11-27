<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-theme-secondary mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :class="[
        'input-base',
        error ? 'border-red-500' : ''
      ]"
    />
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
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
  error?: string;
  autocomplete?: string;
}>();

defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>
