<template>
  <div class="table-container">
    <table class="table">
      <thead class="table-header">
        <tr>
          <th v-for="(column, index) in columns" :key="index" class="table-header-cell">
            {{ column }}
          </th>
        </tr>
      </thead>
      <tbody class="table-body">
        <slot />
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  columns: string[];
}>();
</script>
