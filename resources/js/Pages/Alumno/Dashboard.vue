<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mi Dashboard</h1>
          <p class="page-subtitle">Bienvenido a tu panel de alumno</p>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Card>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-theme-secondary">Cursos Activos</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.cursos_activos }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-theme-secondary">Cursos Completados</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.cursos_completados }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-theme-secondary">Saldo Pendiente</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">Bs {{ stats.saldo_pendiente }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </Card>
      </div>

      <!-- Mis Cursos -->
      <Card v-if="misCursos.length > 0">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-theme-primary">Mis Cursos</h3>
          <Link :href="route('alumno.cursos')">
            <Button variant="ghost" size="sm">Ver todos →</Button>
          </Link>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="curso in misCursos" :key="curso.id" class="border border-theme rounded-lg p-4 hover:bg-theme-secondary transition-colors">
            <h4 class="font-semibold text-theme-primary mb-1">{{ curso.nombre }}</h4>
            <p class="text-sm text-theme-secondary mb-1">{{ curso.tipo }}</p>
            <p v-if="curso.edicion_id" class="text-xs text-theme-tertiary mb-3">
              Edición #{{ curso.edicion_id }} • {{ curso.fecha_inicio }} - {{ curso.fecha_fin }}
            </p>
            <Link :href="route('alumno.cursos.show', curso.inscripcion_id)">
              <Button variant="ghost" size="sm">Ver detalles</Button>
            </Link>
          </div>
        </div>
      </Card>

      <Card v-else>
        <div class="text-center py-8">
          <svg class="w-16 h-16 mx-auto text-theme-tertiary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          <p class="text-theme-secondary mb-4">No estás inscrito en ningún curso</p>
          <Link :href="route('alumno.cursos.disponibles')">
            <Button variant="primary">Explorar Cursos Disponibles</Button>
          </Link>
        </div>
      </Card>

      <!-- Próximas Clases -->
      <Card v-if="proximasClases.length > 0">
        <h3 class="text-lg font-semibold text-theme-primary mb-4">Próximas Clases</h3>
        <div class="space-y-3">
          <div v-for="(item, index) in proximasClases" :key="index" class="border-l-4 border-blue-500 bg-theme-secondary p-4 rounded">
            <div class="flex flex-col gap-1">
              <h4 class="font-semibold text-theme-primary">{{ item.curso }}</h4>
              <span v-if="item.edicion_id" class="text-xs text-theme-tertiary">(Edición #{{ item.edicion_id }})</span>
            </div>
            <div class="mt-2 space-y-1">
              <div v-for="(horario, idx) in item.horarios" :key="idx" class="flex items-center text-sm text-theme-secondary">
                <span class="font-medium mr-2">{{ getDiaNombre(horario.dia) }}:</span>
                <span>{{ horario.hora_inicio }} - {{ horario.hora_fin }}</span>
              </div>
            </div>
          </div>
        </div>
      </Card>

    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';

defineProps<{
  stats: {
    cursos_activos: number;
    cursos_completados: number;
    saldo_pendiente: number;
  };
  misCursos: any[];
  proximasClases: any[];
}>();

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || 'N/A';
};
</script>
