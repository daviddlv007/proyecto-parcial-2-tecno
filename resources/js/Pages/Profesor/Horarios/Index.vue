<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mi Horario Semanal</h1>
          <p class="page-subtitle">{{ totalClases }} clases programadas</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
        <Card v-for="dia in [1, 2, 3, 4, 5, 6, 7]" :key="dia" class="min-h-[200px]">
          <h3 class="font-bold text-theme-primary mb-3 text-center pb-2 border-b border-theme">
            {{ diasNombres[dia] }}
          </h3>
          <div class="space-y-2">
            <div v-if="horariosSemana[dia].length === 0" class="text-center py-8 text-theme-secondary text-sm">
              Sin clases
            </div>
            <div v-for="(horario, idx) in horariosSemana[dia]" :key="idx" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
              <p class="font-semibold text-sm text-blue-900 dark:text-blue-100 mb-1">{{ horario.curso_nombre }}</p>
              <div class="flex items-center text-xs text-blue-700 dark:text-blue-300 gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ horario.hora_inicio }} - {{ horario.hora_fin }}</span>
              </div>
              <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ horario.duracion }}</p>
              <p v-if="horario.fecha_inicio" class="text-xs text-blue-500 dark:text-blue-300 mt-1">
                📅 {{ horario.fecha_inicio }} - {{ horario.fecha_fin }}
              </p>
            </div>
          </div>
        </Card>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';

defineProps<{
  horariosSemana: Record<number, any[]>;
  diasNombres: Record<number, string>;
  totalClases: number;
}>();
</script>
