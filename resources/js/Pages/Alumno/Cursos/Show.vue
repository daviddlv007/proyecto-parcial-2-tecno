<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <Link :href="route('alumno.cursos')">
          <Button variant="ghost" size="sm">← Volver</Button>
        </Link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Curso -->
        <div class="lg:col-span-2 space-y-6">
          <Card>
            <h2 class="text-2xl font-bold text-theme-primary mb-4">{{ curso.nombre }}</h2>
            <div class="space-y-3">
              <div><span class="text-theme-secondary">Tipo:</span> <span class="font-medium">{{ curso.tipo }}</span></div>
              <div><span class="text-theme-secondary">Profesor:</span> <span class="font-medium">{{ curso.profesor }}</span></div>
              <div><span class="text-theme-secondary">Estado:</span> <Badge :variant="curso.estado === 'activo' ? 'success' : 'neutral'">{{ curso.estado }}</Badge></div>
              <div v-if="curso.descripcion"><span class="text-theme-secondary">Descripción:</span> <p class="mt-1">{{ curso.descripcion }}</p></div>
            </div>
          </Card>

          <!-- Horarios -->
          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-3">Horarios de Clase</h3>
            <div v-if="curso.horarios.length > 0" class="space-y-2">
              <div v-for="(horario, index) in curso.horarios" :key="index" class="flex items-center gap-3 p-3 bg-theme-secondary rounded-lg">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-theme-primary">{{ getDiaNombre(horario.dia) }}</p>
                  <p class="text-sm text-theme-secondary">{{ horario.hora_inicio }} - {{ horario.hora_fin }}</p>
                </div>
              </div>
            </div>
            <p v-else class="text-theme-secondary">No hay horarios definidos.</p>
          </Card>

          <!-- Historial de Pagos -->
          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-3">Historial de Pagos</h3>
            <div v-if="pagos.length > 0" class="overflow-x-auto">
              <table class="table">
                <thead class="table-header">
                  <tr>
                    <th class="table-header-cell">Fecha</th>
                    <th class="table-header-cell">Monto</th>
                    <th class="table-header-cell">Método</th>
                  </tr>
                </thead>
                <tbody class="table-body">
                  <tr v-for="pago in pagos" :key="pago.id" class="table-row">
                    <td class="table-cell">{{ pago.fecha }}</td>
                    <td class="table-cell font-medium text-green-600 dark:text-green-400">Bs {{ pago.monto }}</td>
                    <td class="table-cell-secondary">{{ pago.metodo }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-theme-secondary">No hay pagos registrados aún.</p>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <Card variant="elevated">
            <h3 class="text-lg font-semibold text-theme-primary mb-4">Resumen Financiero</h3>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-theme-tertiary">Monto Total</p>
                <p class="text-2xl font-bold text-theme-primary">Bs {{ curso.monto_total }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Saldo Pendiente</p>
                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">Bs {{ curso.saldo_pendiente }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Plan de Pago</p>
                <p class="text-sm font-medium">{{ curso.plan_pago }}</p>
              </div>
            </div>
            <Link v-if="curso.saldo_pendiente > 0" :href="`${route('alumno.pagos.create')}?inscripcion_id=${curso.inscripcion_id}`" class="mt-4 block">
              <Button variant="primary" class="w-full">Realizar Pago</Button>
            </Link>
          </Card>

          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-3">Fechas</h3>
            <div class="space-y-2">
              <div><span class="text-xs text-theme-tertiary">Inicio:</span> <p class="font-medium">{{ curso.fecha_inicio }}</p></div>
              <div><span class="text-xs text-theme-tertiary">Fin:</span> <p class="font-medium">{{ curso.fecha_fin || 'N/A' }}</p></div>
            </div>
          </Card>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';

defineProps<{
  curso: any;
  pagos: any[];
}>();

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || 'N/A';
};
</script>
