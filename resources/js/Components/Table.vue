<template>
  <div class="overflow-x-auto rounded-lg table-border">
    <table class="min-w-full divide-y table-border">
      <thead class="table-head-bg">
        <tr>
          <slot name="headers" />
        </tr>
      </thead>
      <tbody class="table-body-bg divide-y table-border">
        <slot name="rows" />
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
</script>
