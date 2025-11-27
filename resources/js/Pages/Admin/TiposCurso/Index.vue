<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Tipos de Curso</h1>
            <p class="page-subtitle">Administración de tipos de cursos de conducción</p>
          </div>
          <Link :href="route('admin.tipos-curso.create')">
            <Button variant="primary" size="lg">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </Button>
          </Link>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Nombre</th>
                  <th class="table-header-cell">Descripción</th>
                  <th class="table-header-cell">Duración</th>
                  <th class="table-header-cell">Nivel</th>
                  <th class="table-header-cell text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="tipo in tiposCurso.data" :key="tipo.id" class="table-row">
                  <td class="table-cell font-medium">{{ tipo.nombre }}</td>
                  <td class="table-cell-secondary">{{ tipo.descripcion }}</td>
                  <td class="table-cell">{{ tipo.duracion_horas }} hrs</td>
                  <td class="table-cell">
                    <Badge :variant="getNivelVariant(tipo.nivel)">{{ tipo.nivel }}</Badge>
                  </td>
                  <td class="table-cell">
                    <div class="flex items-center justify-end gap-2">
                      <Link :href="route('admin.tipos-curso.edit', tipo.id)">
                        <Button variant="ghost" size="sm">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                          </svg>
                        </Button>
                      </Link>
                      <Button variant="ghost" size="sm" @click="deleteTipo(tipo.id)">
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

          <div class="p-4 border-t border-theme">
            <Pagination :data="tiposCurso" />
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

defineProps<{ tiposCurso: { data: any[] } }>();

const getNivelVariant = (nivel: string) => {
  const variants = { 'básico': 'success', 'intermedio': 'info', 'avanzado': 'warning' };
  return variants[nivel?.toLowerCase()] || 'neutral';
};

const deleteTipo = (id: number) => {
  if (confirm('¿Eliminar este tipo de curso?')) {
    router.delete(route('admin.tipos-curso.destroy', id));
  }
};
</script>
