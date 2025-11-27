<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Mis Cursos</h1>
          <p class="page-subtitle">Todos los cursos asignados</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card v-for="curso in cursos" :key="curso.id" variant="hover">
          <div class="space-y-4">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-xl font-bold text-theme-primary">{{ curso.nombre }}</h3>
                <p class="text-sm text-theme-secondary mt-1">{{ curso.tipo }}</p>
              </div>
              <Badge :variant="curso.estado === 'activo' ? 'success' : 'neutral'">
                {{ curso.estado }}
              </Badge>
            </div>

            <p class="text-sm text-theme-secondary">{{ curso.descripcion || 'Sin descripción' }}</p>

            <div class="pt-3 border-t border-theme">
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <p class="text-xs text-theme-secondary">Capacidad Total</p>
                  <p class="text-lg font-semibold text-theme-primary">{{ curso.inscritos }} / {{ curso.capacidad_maxima }}</p>
                  <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all" :style="`width: ${curso.ocupacion}%`"></div>
                  </div>
                </div>
                <div>
                  <p class="text-xs text-theme-secondary">Ediciones</p>
                  <p class="text-lg font-semibold text-theme-primary">{{ curso.ediciones_count }}</p>
                  <p v-if="curso.proxima_edicion" class="text-xs text-theme-tertiary mt-1">Próxima: {{ curso.proxima_edicion }}</p>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-theme">
              <p class="text-xs text-theme-secondary mb-2">Próximas Ediciones (con horarios)</p>
              <p class="text-xs text-theme-tertiary">Consulta detalles para ver ediciones y horarios específicos</p>
            </div>

            <div class="pt-3 flex items-center justify-between">
              <div class="text-sm text-theme-secondary">
                {{ curso.inscritos_activos }} alumnos activos
              </div>
              <Link :href="route('profesor.cursos.show', curso.id)">
                <Button variant="primary" size="sm">Ver Detalles</Button>
              </Link>
            </div>
          </div>
        </Card>
      </div>

      <div v-if="cursos.length === 0" class="text-center py-12">
        <p class="text-theme-secondary">No tienes cursos asignados actualmente</p>
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
