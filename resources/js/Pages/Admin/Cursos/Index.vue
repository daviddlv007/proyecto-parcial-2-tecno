<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Gestión de Cursos</h1>
            <p class="page-subtitle">Administra todos los cursos de la escuela de conducción</p>
          </div>
          <Link :href="route('admin.cursos.create')">
            <Button variant="primary" size="lg">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </Button>
          </Link>
        </div>

        <!-- Table Card -->
        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Nombre</th>
                  <th class="table-header-cell">Tipo</th>
                  <th class="table-header-cell">Instructor</th>
                  <th class="table-header-cell">Precio Base</th>
                  <th class="table-header-cell">Capacidad Máx</th>
                  <th class="table-header-cell">Estado</th>
                  <th class="table-header-cell">Ediciones</th>
                  <th class="table-header-cell text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="curso in cursos.data" :key="curso.id" class="table-row">
                  <td class="table-cell font-medium">{{ curso.nombre }}</td>
                  <td class="table-cell-secondary">{{ curso.tipo_curso?.nombre || '-' }}</td>
                  <td class="table-cell-secondary">
                    {{ curso.instructor ? `${curso.instructor.nombre} ${curso.instructor.apellido}` : '-' }}
                  </td>
                  <td class="table-cell">Bs. {{ Number(curso.precio || 0).toFixed(2) }}</td>
                  <td class="table-cell-secondary">{{ curso.capacidad_maxima }}</td>
                  <td class="table-cell">
                    <Badge :variant="getEstadoVariant(curso.estado_curso)">
                      {{ curso.estado_curso }}
                    </Badge>
                  </td>
                  <td class="table-cell">
                    <div class="flex items-center gap-2">
                      <Badge :variant="curso.ediciones_count > 0 ? 'info' : 'neutral'">
                        {{ curso.ediciones_count }} {{ curso.ediciones_count === 1 ? 'edición' : 'ediciones' }}
                      </Badge>
                      <Link v-if="curso.ediciones_count > 0" :href="route('admin.curso-ediciones.index', { curso: curso.id })">
                        <Button variant="ghost" size="sm">Ver</Button>
                      </Link>
                    </div>
                  </td>
                  <td class="table-cell">
                    <div class="flex items-center justify-end gap-2">
                      <Link :href="route('admin.cursos.edit', curso.id)">
                        <Button variant="ghost" size="sm">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                          </svg>
                        </Button>
                      </Link>
                      <Button variant="ghost" size="sm" @click="deleteCurso(curso.id)">
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
            <Pagination :data="cursos" />
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  cursos: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();

const getEstadoVariant = (estado: string) => {
  const variants = {
    'activo': 'success',
    'inactivo': 'neutral',
    'completo': 'info'
  };
  return variants[estado] || 'neutral';
};

const deleteCurso = (id: number) => {
  if (confirm('¿Está seguro de eliminar este curso?')) {
    router.delete(route('admin.cursos.destroy', id) as string);
  }
};
</script>
