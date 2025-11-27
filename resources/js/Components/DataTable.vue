<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
      <div class="flex items-center space-x-3">
        <!-- Search -->
        <div class="relative">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        
        <!-- Add Button -->
        <button
          v-if="showAddButton"
          @click="emit('add')"
          class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          <span>Nuevo</span>
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              {{ column.label }}
            </th>
            <th v-if="showActions" scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Acciones
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="(item, index) in filteredData" :key="index" class="hover:bg-gray-50 transition-colors">
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
            >
              <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">
                {{ item[column.key] }}
              </slot>
            </td>
            <td v-if="showActions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <slot name="actions" :item="item">
                <button
                  @click="emit('edit', item)"
                  class="text-blue-600 hover:text-blue-900"
                >
                  Editar
                </button>
                <button
                  @click="emit('delete', item)"
                  class="text-red-600 hover:text-red-900"
                >
                  Eliminar
                </button>
              </slot>
            </td>
          </tr>
          <tr v-if="filteredData.length === 0">
            <td :colspan="columns.length + (showActions ? 1 : 0)" class="px-6 py-8 text-center text-gray-500">
              No hay datos para mostrar
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="filteredData.length > 0" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
      <div class="text-sm text-gray-700">
        Mostrando <span class="font-medium">{{ filteredData.length }}</span> de <span class="font-medium">{{ data.length }}</span> resultados
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  data: {
    type: Array,
    required: true,
  },
  showActions: {
    type: Boolean,
    default: true,
  },
  showAddButton: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['add', 'edit', 'delete']);

const search = ref('');

const filteredData = computed(() => {
  if (!search.value) return props.data;
  
  const searchLower = search.value.toLowerCase();
  return props.data.filter(item => {
    return props.columns.some(column => {
      const value = item[column.key];
      return value && value.toString().toLowerCase().includes(searchLower);
    });
  });
});
</script>
