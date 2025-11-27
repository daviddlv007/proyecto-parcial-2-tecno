<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mis Planes de Pago</h1>
          <p class="page-subtitle">Administra tus cuotas y vencimientos</p>
        </div>
      </div>

      <div v-if="planesPago.length === 0" class="text-center py-12">
        <Card>
          <div class="py-8">
            <svg class="w-16 h-16 mx-auto text-theme-tertiary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-theme-secondary">No tienes planes de pago pendientes. ¡Estás al día!</p>
            <Link :href="route('alumno.cursos.disponibles')" class="mt-4 inline-block">
              <Button variant="primary">Ver Cursos Disponibles</Button>
            </Link>
          </div>
        </Card>
      </div>

      <div v-else class="space-y-4">
        <Card v-for="plan in planesPago" :key="plan.inscripcion_id" :class="[
          plan.estado === 'moroso' ? 'border-2 border-red-500' : 
          plan.estado === 'proximo' ? 'border-2 border-orange-400' : ''
        ]">
          <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-lg font-bold text-theme-primary">{{ plan.curso }}</h3>
                <p class="text-sm text-theme-secondary mt-1">{{ plan.plan_pago }}</p>
              </div>
              <Badge :variant="plan.estado === 'moroso' ? 'error' : plan.estado === 'proximo' ? 'warning' : 'success'">
                {{ plan.estado === 'moroso' ? 'MOROSO' : plan.estado === 'proximo' ? 'PRÓXIMO' : 'VIGENTE' }}
              </Badge>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-theme">
              <div>
                <p class="text-xs text-theme-tertiary">Monto Total</p>
                <p class="text-lg font-bold text-theme-primary">Bs {{ plan.monto_total }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Saldo Pendiente</p>
                <p class="text-lg font-bold text-orange-600 dark:text-orange-400">Bs {{ plan.saldo_pendiente }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Monto Cuota</p>
                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">Bs {{ plan.monto_cuota }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Cuotas Pendientes</p>
                <p class="text-lg font-bold text-theme-primary">{{ plan.cuotas_pendientes }}</p>
              </div>
            </div>

            <!-- Vencimiento y Acción -->
            <div class="flex items-center justify-between bg-theme-secondary p-4 rounded-lg">
              <div>
                <p class="text-xs text-theme-tertiary">Próximo Vencimiento</p>
                <p class="font-semibold text-theme-primary">{{ plan.proximo_vencimiento }}</p>
                <p class="text-xs mt-1" :class="plan.dias_para_vencimiento < 0 ? 'text-red-600 dark:text-red-400' : 'text-theme-secondary'">
                  {{ plan.dias_para_vencimiento < 0 ? `${Math.abs(plan.dias_para_vencimiento)} días de retraso` : `${plan.dias_para_vencimiento} días restantes` }}
                </p>
              </div>
              <Link :href="`${route('alumno.pagos.create')}?inscripcion_id=${plan.inscripcion_id}`">
                <Button :variant="plan.estado === 'moroso' ? 'danger' : 'primary'">
                  {{ plan.estado === 'moroso' ? 'Pagar Ahora' : 'Realizar Pago' }}
                </Button>
              </Link>
            </div>
          </div>
        </Card>
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
  planesPago: any[];
}>();
</script>
