<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mis Pagos</h1>
          <p class="page-subtitle">Gestiona tus pagos e inscripciones</p>
        </div>
        <Link v-if="inscripcionesPendientes.length > 0" :href="route('alumno.pagos.create')">
          <Button variant="primary">Realizar Pago</Button>
        </Link>
      </div>

      <!-- Inscripciones Pendientes -->
      <Card v-if="inscripcionesPendientes.length > 0">
        <h3 class="text-lg font-semibold text-theme-primary mb-4">Pagos Pendientes</h3>
        <div class="space-y-3">
          <div v-for="inscripcion in inscripcionesPendientes" :key="inscripcion.inscripcion_id" class="border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-semibold text-theme-primary">{{ inscripcion.curso }}</h4>
                <p class="text-sm text-theme-secondary mt-1">Saldo: <span class="font-bold text-orange-600 dark:text-orange-400">Bs {{ inscripcion.saldo_pendiente }}</span></p>
                <p class="text-xs text-theme-tertiary mt-1">Cuota sugerida: Bs {{ inscripcion.monto_cuota }}</p>
              </div>
              <Link :href="`${route('alumno.pagos.create')}?inscripcion_id=${inscripcion.inscripcion_id}`">
                <Button variant="primary" size="sm">Pagar Ahora</Button>
              </Link>
            </div>
          </div>
        </div>
      </Card>

      <!-- Historial de Pagos -->
      <Card :padding="false">
        <div class="p-6 border-b border-theme">
          <h3 class="text-lg font-semibold text-theme-primary">Historial de Pagos</h3>
        </div>
        <div v-if="pagos.length > 0" class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Fecha</th>
                <th class="table-header-cell">Curso</th>
                <th class="table-header-cell">Monto</th>
                <th class="table-header-cell">Método</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="pago in pagos" :key="pago.id" class="table-row">
                <td class="table-cell">{{ pago.fecha }}</td>
                <td class="table-cell">{{ pago.curso }}</td>
                <td class="table-cell font-medium text-green-600 dark:text-green-400">Bs {{ pago.monto }}</td>
                <td class="table-cell-secondary">{{ pago.metodo }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="p-6 text-center text-theme-secondary">
          No hay pagos registrados aún.
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
  pagos: any[];
  inscripcionesPendientes: any[];
}>();
</script>
