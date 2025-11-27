<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Gestión de Roles</h1>
            <p class="page-subtitle">Administra los roles del sistema</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Nombre</th>
                  <th class="table-header-cell">Descripción</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-if="roles && roles.data && roles.data.length > 0" v-for="rol in roles.data" :key="rol.id" class="table-row">
                  <td class="table-cell">
                    <div class="flex items-center gap-3">
                      <Badge :variant="getRolVariant(rol.nombre)">{{ rol.nombre }}</Badge>
                      <span class="text-xs text-theme-tertiary">ID: {{ rol.id }}</span>
                    </div>
                  </td>
                  <td class="table-cell-secondary">{{ rol.descripcion }}</td>
                </tr>
                <tr v-else>
                  <td colspan="2" class="table-cell text-center py-8">
                    <p class="text-theme-tertiary">No hay roles disponibles</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';

defineProps<{
  roles?: {
    data?: any[];
    links?: any[];
    from?: number;
    to?: number;
    total?: number;
  };
}>();

const getRolVariant = (nombre: string) => {
  const variants = {
    'administrador': 'error',
    'instructor': 'info',
    'alumno': 'success'
  };
  return variants[nombre?.toLowerCase()] || 'neutral';
};
</script>
