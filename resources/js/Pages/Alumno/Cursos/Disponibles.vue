<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <h1 class="page-title">Cursos Disponibles</h1>
        <p class="page-subtitle">Explora y inscríbete en nuestros cursos</p>
      </div>

      <div v-if="ediciones.length === 0" class="text-center py-12">
        <p class="text-theme-secondary">No hay cursos disponibles en este momento.</p>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card v-for="edicion in ediciones" :key="edicion.id" variant="hover">
          <div class="space-y-4">
            <div>
              <h3 class="text-xl font-bold text-theme-primary">{{ edicion.nombre }}</h3>
              <Badge class="mt-2">{{ edicion.tipo }}</Badge>
            </div>

            <p v-if="edicion.descripcion" class="text-sm text-theme-secondary">{{ edicion.descripcion }}</p>

            <div class="grid grid-cols-2 gap-4 py-3 border-y border-theme">
              <div>
                <p class="text-xs text-theme-tertiary">Precio</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">Bs {{ edicion.precio }}</p>
              </div>
              <div>
                <p class="text-xs text-theme-tertiary">Cupo disponible</p>
                <p class="text-sm font-medium">{{ edicion.cupo_disponible }}/{{ edicion.cupo_total }}</p>
              </div>
            </div>

            <div>
              <p class="text-xs text-theme-tertiary mb-2">Fechas</p>
              <p class="text-sm font-medium">{{ edicion.fecha_inicio }} - {{ edicion.fecha_fin }}</p>
            </div>

            <div>
              <p class="text-xs text-theme-tertiary mb-2">Profesor</p>
              <p class="text-sm font-medium">{{ edicion.profesor }}</p>
            </div>

            <div v-if="edicion.horarios.length > 0">
              <p class="text-xs text-theme-tertiary mb-2">Horarios</p>
              <div class="flex flex-wrap gap-2">
                <div v-for="(horario, idx) in edicion.horarios" :key="idx" class="text-xs bg-theme-secondary px-2 py-1 rounded">
                  {{ getDiaNombre(horario.dia) }}: {{ horario.hora_inicio }}-{{ horario.hora_fin }}
                </div>
              </div>
            </div>

            <Link :href="route('alumno.cursos.inscribirse', edicion.id)" class="block">
              <Button variant="primary" class="w-full">Inscribirme</Button>
            </Link>
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
  ediciones: any[];
}>();

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
  return dias[dia] || 'N/A';
};
</script>
