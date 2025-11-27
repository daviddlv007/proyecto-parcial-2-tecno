<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Dashboard Profesor</h1>
          <p class="page-subtitle">Vista general de tus cursos y alumnos</p>
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
              <p class="text-sm text-theme-secondary">Total Alumnos</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.total_alumnos }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-theme-secondary">Inscripciones Activas</p>
              <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ stats.inscripciones_activas }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
        </Card>
      </div>

      <!-- Próximas Clases -->
      <Card v-if="proximasClases.length > 0">
        <h3 class="text-lg font-semibold text-theme-primary mb-4">Próximas Clases</h3>
        <div class="space-y-3">
          <div v-for="(item, index) in proximasClases" :key="index" class="border-l-4 border-blue-500 bg-theme-secondary p-4 rounded-lg">
            <h4 class="font-semibold text-theme-primary">{{ item.curso.nombre }}</h4>
            <div class="mt-2 space-y-1">
              <div v-if="item.hoy.length > 0" class="flex items-start gap-2">
                <span class="font-medium text-blue-600 dark:text-blue-400 text-sm min-w-[60px]">Hoy:</span>
                <div class="flex-1">
                  <div v-for="(horario, idx) in item.hoy" :key="idx" class="text-sm mb-1">
                    <span class="font-medium">{{ horario.hora_inicio }} - {{ horario.hora_fin }}</span>
                    <span v-if="horario.edicion_id" class="text-xs text-theme-secondary ml-2">(Edición #{{ horario.edicion_id }})</span>
                  </div>
                </div>
              </div>
              <div v-if="item.manana.length > 0" class="flex items-start gap-2">
                <span class="font-medium text-theme-secondary text-sm min-w-[60px]">Mañana:</span>
                <div class="flex-1">
                  <div v-for="(horario, idx) in item.manana" :key="idx" class="text-sm mb-1">
                    <span>{{ horario.hora_inicio }} - {{ horario.hora_fin }}</span>
                    <span v-if="horario.edicion_id" class="text-xs text-theme-tertiary ml-2">(Edición #{{ horario.edicion_id }})</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <!-- Mis Cursos Recientes -->
      <Card>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-theme-primary">Mis Cursos</h3>
          <Link :href="route('profesor.cursos')">
            <Button variant="ghost" size="sm">Ver todos →</Button>
          </Link>
        </div>
        <div class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Curso</th>
                <th class="table-header-cell">Tipo</th>
                <th class="table-header-cell">Ocupación</th>
                <th class="table-header-cell text-right">Acción</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="curso in cursosRecientes" :key="curso.id" class="table-row">
                <td class="table-cell font-medium">{{ curso.nombre }}</td>
                <td class="table-cell-secondary">{{ curso.tipo }}</td>
                <td class="table-cell">
                  <div class="flex items-center gap-2">
                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 max-w-[100px]">
                      <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${curso.ocupacion}%`"></div>
                    </div>
                    <span class="text-sm">{{ curso.inscritos }}/{{ curso.capacidad_maxima }}</span>
                  </div>
                </td>
                <td class="table-cell text-right">
                  <Link :href="route('profesor.cursos.show', curso.id)">
                    <Button variant="ghost" size="sm">Ver</Button>
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
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
    total_alumnos: number;
    inscripciones_activas: number;
  };
  proximasClases: any[];
  cursosRecientes: any[];
}>();
</script>
