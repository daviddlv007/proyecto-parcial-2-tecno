<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Registro de Eventos</h1>
            <p class="page-subtitle">Historial de eventos del sistema</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">ID</th>
                  <th class="table-header-cell">Fecha/Hora</th>
                  <th class="table-header-cell">Usuario</th>
                  <th class="table-header-cell">Ruta</th>
                  <th class="table-header-cell">Descripción</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="evento in eventos.data" :key="evento.id" class="table-row">
                  <td class="table-cell">{{ evento.id }}</td>
                  <td class="table-cell">{{ evento.fecha_evento }}</td>
                  <td class="table-cell-secondary">
                    {{ evento.usuario ? `${evento.usuario.nombre} ${evento.usuario.apellido}` : 'Sistema' }}
                  </td>
                  <td class="table-cell">{{ evento.ruta }}</td>
                  <td class="table-cell-secondary">{{ evento.descripcion }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-4 border-t border-theme">
            <Pagination :data="eventos" />
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  eventos: {
    data: any[];
    links?: any[];
    from?: number;
    to?: number;
    total?: number;
  };
}>();
</script>
