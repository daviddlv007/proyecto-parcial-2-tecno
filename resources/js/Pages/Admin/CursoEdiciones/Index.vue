<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <h1 class="page-title">Ediciones de Cursos</h1>
          <p class="page-subtitle">Gestiona las ediciones programadas de cada curso</p>
        </div>
        <Link :href="route('admin.curso-ediciones.create')">
          <Button variant="primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
          </Button>
        </Link>
      </div>

      <Card>
        <div class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Curso</th>
                <th class="table-header-cell">Fechas</th>
                <th class="table-header-cell">Cupo</th>
                <th class="table-header-cell">Horarios</th>
                <th class="table-header-cell">Precio</th>
                <th class="table-header-cell">Estado</th>
                <th class="table-header-cell">Acciones</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="edicion in ediciones.data" :key="edicion.id" class="table-row">
                <td class="table-cell">
                  <div>
                    <p class="font-medium">{{ edicion.curso_nombre }}</p>
                    <p class="text-xs text-theme-secondary">{{ edicion.tipo_curso }}</p>
                  </div>
                </td>
                <td class="table-cell-secondary">
                  <div>
                    <p class="text-sm">{{ edicion.fecha_inicio }}</p>
                    <p class="text-xs text-theme-tertiary">hasta {{ edicion.fecha_fin }}</p>
                  </div>
                </td>
                <td class="table-cell-secondary">
                  <div class="flex items-center gap-2">
                    <span>{{ edicion.cupo_disponible }}/{{ edicion.cupo_total }}</span>
                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                      <div 
                        class="bg-blue-600 h-1.5 rounded-full" 
                        :style="`width: ${((edicion.cupo_total - edicion.cupo_disponible) / edicion.cupo_total * 100)}%`"
                      ></div>
                    </div>
                  </div>
                </td>
                <td class="table-cell-secondary">
                  <Button 
                    @click="verHorarios(edicion.id)" 
                    variant="ghost" 
                    size="sm"
                  >
                    <Badge variant="info">{{ edicion.horarios_count }} horario{{ edicion.horarios_count !== 1 ? 's' : '' }}</Badge>
                  </Button>
                </td>
                <td class="table-cell">Bs. {{ Number(edicion.precio_final || 0).toFixed(2) }}</td>
                <td class="table-cell">
                  <Badge :variant="getEstadoBadge(edicion.estado)">
                    {{ edicion.estado }}
                  </Badge>
                </td>
                <td class="table-cell">
                  <div class="flex items-center justify-end gap-2">
                    <Link :href="route('admin.curso-ediciones.edit', edicion.id)">
                      <Button variant="ghost" size="sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                      </Button>
                    </Link>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      @click="confirmarEliminar(edicion.id)"
                      :disabled="edicion.inscritos > 0"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                      </svg>
                    </Button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-theme">
          <Pagination :data="ediciones" />
        </div>
      </Card>

      <!-- Modal Horarios -->
      <div v-if="modalHorarios" @click.self="modalHorarios = null" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <Card class="max-w-lg w-full mx-4">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold text-theme-primary">Horarios de la Edición</h3>
              <Button @click="modalHorarios = null" variant="ghost" size="sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </Button>
            </div>
            
            <div v-if="modalHorarios.horarios && modalHorarios.horarios.length > 0" class="space-y-2">
              <div v-for="horario in modalHorarios.horarios" :key="horario.id" 
                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center space-x-3">
                  <Badge variant="info">{{ getDiaNombre(horario.dia_semana) }}</Badge>
                  <span class="text-sm text-theme-secondary">
                    {{ horario.hora_inicio }} - {{ horario.hora_fin }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-theme-tertiary">
              No hay horarios configurados
            </div>

            <div class="mt-4 flex justify-end">
              <Link :href="route('admin.curso-ediciones.edit', modalHorarios.id)">
                <Button variant="primary" size="sm">Editar Edición</Button>
              </Link>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  ediciones: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();

const modalHorarios = ref<any>(null);

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || '';
};

const verHorarios = async (edicionId: number) => {
  // Fetch horarios from backend
  const response = await fetch(`/admin/curso-ediciones/${edicionId}/horarios-data`);
  const data = await response.json();
  modalHorarios.value = data;
};

const getEstadoBadge = (estado: string) => {
  const map: Record<string, string> = {
    'programada': 'info',
    'en_curso': 'success',
    'finalizada': 'neutral',
    'cancelada': 'error'
  };
  return map[estado] || 'neutral';
};

const confirmarEliminar = (id: number) => {
  if (confirm('¿Estás seguro de eliminar esta edición? Esta acción no se puede deshacer.')) {
    router.delete(route('admin.curso-ediciones.destroy', id));
  }
};
</script>
