<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mis Alumnos</h1>
          <p class="page-subtitle">Todos los alumnos en tus cursos</p>
        </div>
      </div>

      <Card>
        <div class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Alumno</th>
                <th class="table-header-cell">Contacto</th>
                <th class="table-header-cell">Cursos</th>
                <th class="table-header-cell text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="alumno in alumnos" :key="alumno.id" class="table-row">
                <td class="table-cell">
                  <div>
                    <p class="font-medium">{{ alumno.nombre }}</p>
                    <p class="text-xs text-theme-secondary">{{ alumno.email }}</p>
                  </div>
                </td>
                <td class="table-cell-secondary">{{ alumno.telefono }}</td>
                <td class="table-cell">
                  <div class="space-y-1">
                    <p class="text-sm">{{ alumno.cursos_inscritos }} curso{{ alumno.cursos_inscritos !== 1 ? 's' : '' }} ({{ alumno.cursos_activos }} activo{{ alumno.cursos_activos !== 1 ? 's' : '' }})</p>
                    <div class="flex flex-wrap gap-1">
                      <span v-for="(curso, idx) in alumno.cursos.slice(0, 2)" :key="idx" class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded text-xs" :title="`Edición ${curso.edicion_id}: ${curso.fecha_inicio} - ${curso.fecha_fin}`">
                        {{ curso.nombre }}
                      </span>
                      <span v-if="alumno.cursos.length > 2" class="px-2 py-0.5 bg-gray-50 dark:bg-gray-700 text-theme-secondary rounded text-xs">
                        +{{ alumno.cursos.length - 2 }}
                      </span>
                    </div>
                  </div>
                </td>
                <td class="table-cell text-right">
                  <Button variant="ghost" size="sm" @click="verDetalle(alumno)">
                    Ver Detalle
                  </Button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Modal de Detalle -->
      <div v-if="alumnoSeleccionado" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="alumnoSeleccionado = null">
        <Card class="max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-theme-primary">{{ alumnoSeleccionado.nombre }}</h3>
            <button @click="alumnoSeleccionado = null" class="text-theme-secondary hover:text-theme-primary">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <p class="text-sm text-theme-secondary">Email</p>
              <p class="font-medium">{{ alumnoSeleccionado.email }}</p>
            </div>
            <div>
              <p class="text-sm text-theme-secondary">Teléfono</p>
              <p class="font-medium">{{ alumnoSeleccionado.telefono }}</p>
            </div>

            <div class="pt-4 border-t border-theme">
              <h4 class="font-semibold mb-3">Cursos Inscritos</h4>
              <div class="space-y-2">
                <div v-for="(curso, idx) in alumnoSeleccionado.cursos" :key="idx" class="flex items-center justify-between p-3 bg-theme-secondary rounded">
                  <div>
                    <p class="font-medium">{{ curso.nombre }}</p>
                    <p v-if="curso.fecha_inicio" class="text-xs text-theme-secondary">
                      📅 {{ curso.fecha_inicio }} - {{ curso.fecha_fin }}
                    </p>
                    <p v-if="curso.edicion_id" class="text-xs text-theme-tertiary">Edición #{{ curso.edicion_id }}</p>
                  </div>
                  <Badge :variant="getBadgeVariant(curso.estado)">{{ curso.estado }}</Badge>
                </div>
              </div>
            </div>
          </div>
        </Card>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';

defineProps<{
  alumnos: any[];
}>();

const alumnoSeleccionado = ref<any>(null);

const verDetalle = (alumno: any) => {
  alumnoSeleccionado.value = alumno;
};

const getBadgeVariant = (estado: string) => {
  const map: Record<string, string> = {
    'activa': 'success',
    'pendiente': 'warning',
    'completada': 'info',
    'cancelada': 'error'
  };
  return map[estado] || 'neutral';
};
</script>
