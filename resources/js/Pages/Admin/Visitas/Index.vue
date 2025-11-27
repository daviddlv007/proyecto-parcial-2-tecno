<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Estadísticas de Visitas</h1>
            <p class="page-subtitle">Monitoreo de visitas por página del sistema</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Ruta</th>
                  <th class="table-header-cell">Visitas</th>
                  <th class="table-header-cell">Último Acceso</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="visita in visitas.data" :key="visita.id" class="table-row">
                  <td class="table-cell">{{ visita.ruta }}</td>
                  <td class="table-cell">
                    <Badge variant="info">{{ visita.contador }}</Badge>
                  </td>
                  <td class="table-cell-secondary">{{ visita.fecha_ultimo_acceso }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-4 border-t border-theme">
            <Pagination :data="visitas" />
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
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  visitas: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();
</script>
