<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <h1 class="page-title">Mis Cursos</h1>
      </div>

      <div v-if="cursos.length === 0">
        <Card>
          <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-theme-tertiary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-theme-secondary mb-4">No tienes cursos inscritos aún.</p>
            <Link :href="route('alumno.cursos.disponibles')">
              <Button variant="primary">Explorar Cursos Disponibles</Button>
            </Link>
          </div>
        </Card>
      </div>

      <div v-else class="grid grid-cols-1 gap-4">
        <Card v-for="curso in cursos" :key="curso.id" variant="hover">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-theme-primary">{{ curso.nombre }}</h3>
              <p class="text-sm text-theme-secondary mt-1">{{ curso.tipo }}</p>
              <p v-if="curso.fecha_inicio && curso.fecha_fin" class="text-xs text-theme-tertiary mt-1">
                📅 {{ curso.fecha_inicio }} - {{ curso.fecha_fin }}
              </p>
              <p v-if="curso.descripcion" class="text-sm text-theme-secondary mt-2">{{ curso.descripcion }}</p>
              
              <div class="mt-4 flex items-center gap-6">
                <div>
                  <p class="text-xs text-theme-tertiary">Estado</p>
                  <Badge :variant="curso.estado === 'activa' ? 'success' : 'neutral'">{{ curso.estado }}</Badge>
                </div>
                <div v-if="curso.saldo_pendiente > 0">
                  <p class="text-xs text-theme-tertiary">Saldo Pendiente</p>
                  <p class="text-sm font-semibold text-orange-600 dark:text-orange-400">Bs {{ curso.saldo_pendiente }}</p>
                </div>
              </div>
            </div>
            
            <div class="flex gap-2">
              <Link :href="route('alumno.cursos.show', curso.inscripcion_id)">
                <Button variant="ghost" size="sm">Ver Detalles</Button>
              </Link>
              <Link v-if="curso.saldo_pendiente > 0" :href="`${route('alumno.pagos.create')}?inscripcion_id=${curso.inscripcion_id}`">
                <Button variant="primary" size="sm">Pagar</Button>
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
  cursos: any[];
}>();
</script>
