<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <Link :href="route('profesor.cursos')" class="text-sm text-blue-600 hover:underline mb-2 inline-block">
            ← Volver a Mis Cursos
          </Link>
          <h1 class="page-title">{{ curso.nombre }}</h1>
          <p class="page-subtitle">{{ curso.tipo }} • {{ curso.estado }}</p>
        </div>
      </div>

      <!-- Información del Curso -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card>
          <p class="text-sm text-theme-secondary">Capacidad Máxima</p>
          <p class="text-2xl font-bold text-theme-primary">{{ curso.capacidad_maxima }} alumnos</p>
        </Card>
        <Card>
          <p class="text-sm text-theme-secondary">Alumnos Inscritos</p>
          <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ inscripciones.length }}</p>
        </Card>
      </div>

      <!-- Descripción y Ediciones -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-3">Descripción</h3>
          <p class="text-theme-secondary">{{ curso.descripcion || 'Sin descripción' }}</p>
        </Card>
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-3">Ediciones Programadas ({{ ediciones.length }})</h3>
          <div class="space-y-3">
            <div v-for="edicion in ediciones" :key="edicion.id" class="p-4 bg-theme-secondary rounded-lg border border-theme">
              <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <Badge :variant="edicion.estado === 'programada' ? 'info' : edicion.estado === 'en_curso' ? 'success' : 'neutral'">
                      {{ edicion.estado }}
                    </Badge>
                    <span class="text-sm font-medium text-theme-primary">Edición #{{ edicion.id }}</span>
                  </div>
                  <p class="text-sm font-medium text-theme-primary">📅 {{ edicion.fecha_inicio }} → {{ edicion.fecha_fin }}</p>
                  <p class="text-xs text-theme-secondary mt-1">
                    👥 {{ edicion.inscritos }}/{{ edicion.cupo_total }} inscritos 
                    <span v-if="edicion.cupo_disponible <= 0" class="text-red-600 dark:text-red-400 font-medium ml-1">(Cupo lleno)</span>
                    <span v-else class="text-green-600 dark:text-green-400 ml-1">({{ edicion.cupo_disponible }} disponibles)</span>
                  </p>
                </div>
              </div>
              <div v-if="edicion.horarios.length > 0" class="mt-3 pt-3 border-t border-theme/50">
                <p class="text-xs text-theme-secondary mb-2">Horarios:</p>
                <div class="flex flex-wrap gap-1">
                  <span v-for="(horario, idx) in edicion.horarios" :key="idx" class="text-xs bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
                    {{ horario.dia_nombre }} {{ horario.hora_inicio }}-{{ horario.hora_fin }}
                  </span>
                </div>
              </div>
            </div>
            <p v-if="ediciones.length === 0" class="text-sm text-theme-tertiary text-center py-4">No hay ediciones programadas</p>
          </div>
        </Card>
      </div>

      <!-- Lista de Alumnos Inscritos -->
      <Card>
        <h3 class="text-lg font-semibold text-theme-primary mb-4">Alumnos Inscritos ({{ inscripciones.length }})</h3>
        <div class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Alumno</th>
                <th class="table-header-cell">Contacto</th>
                <th class="table-header-cell">Edición</th>
                <th class="table-header-cell">Estado</th>
                <th class="table-header-cell">Plan</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="inscripcion in inscripciones" :key="inscripcion.id" class="table-row">
                <td class="table-cell">
                  <div>
                    <p class="font-medium">{{ inscripcion.alumno.nombre }}</p>
                    <p class="text-xs text-theme-secondary">{{ inscripcion.alumno.email }}</p>
                  </div>
                </td>
                <td class="table-cell-secondary">{{ inscripcion.alumno.telefono }}</td>
                <td class="table-cell-secondary">
                  <div>
                    <p class="text-xs font-medium text-theme-primary">Edición #{{ inscripcion.edicion_id }}</p>
                    <p class="text-xs text-theme-tertiary">{{ inscripcion.fecha_inicio }} - {{ inscripcion.fecha_fin }}</p>
                    <p class="text-xs text-theme-secondary mt-0.5">Inscrito: {{ inscripcion.fecha_inscripcion }}</p>
                  </div>
                </td>
                <td class="table-cell">
                  <Badge :variant="getBadgeVariant(inscripcion.estado)">
                    {{ inscripcion.estado }}
                  </Badge>
                </td>
                <td class="table-cell-secondary">{{ inscripcion.plan_pago }}</td>
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
import Badge from '@/Components/UI/Badge.vue';

defineProps<{
  curso: any;
  ediciones: any[];
  inscripciones: any[];
}>();

const getBadgeVariant = (estado: string) => {
  const map: Record<string, string> = {
    'activa': 'success',
    'completada': 'info',
    'cancelada': 'error',
    'suspendida': 'warning'
  };
  return map[estado] || 'neutral';
};
</script>
