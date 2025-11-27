<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <h1 class="page-title">Mi Horario</h1>
        <p class="page-subtitle">Visualiza tus clases semanales</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <Card v-for="dia in [1, 2, 3, 4, 5, 6, 7]" :key="dia" class="min-h-[200px]">
          <h3 class="font-semibold text-theme-primary mb-3 border-b border-theme pb-2">{{ getDiaNombre(dia) }}</h3>
          
          <div v-if="horariosPorDia[dia] && horariosPorDia[dia].length > 0" class="space-y-2">
            <div v-for="(horario, index) in horariosPorDia[dia]" :key="index" class="p-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded">
              <p class="font-medium text-sm text-theme-primary">{{ horario.curso }}</p>
              <p v-if="horario.edicion_id" class="text-xs text-theme-tertiary">Edición #{{ horario.edicion_id }}</p>
              <p class="text-xs text-theme-secondary mt-1">{{ horario.hora_inicio }} - {{ horario.hora_fin }}</p>
              <p v-if="horario.fecha_inicio && horario.fecha_fin" class="text-xs text-theme-tertiary mt-1">
                📅 {{ horario.fecha_inicio }} - {{ horario.fecha_fin }}
              </p>
            </div>
          </div>
          
          <div v-else class="text-center text-theme-tertiary text-sm py-8">
            Sin clases
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
  horariosPorDia: Record<number, any[]>;
}>();

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || 'N/A';
};
</script>
