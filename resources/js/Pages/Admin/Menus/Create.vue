<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header mb-6">
          <h1 class="page-title">Nuevo Menú</h1>
          <p class="page-subtitle">Agrega un elemento al menú de navegación</p>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <Input
              v-model="form.nombre"
              label="Nombre"
              placeholder="Dashboard, Usuarios, Reportes..."
              required
              :error="form.errors.nombre"
            />

            <Input
              v-model="form.ruta"
              label="Ruta"
              placeholder="admin.dashboard, admin.usuarios.index..."
              required
              hint="Nombre de la ruta Laravel"
              :error="form.errors.ruta"
            />

            <Input
              v-model="form.orden"
              type="number"
              label="Orden"
              required
              hint="Posición en el menú (menor número = más arriba)"
              :error="form.errors.orden"
            />

            <Select
              v-model="form.rol_id"
              label="Rol (Opcional)"
              :options="[
                {value: null, label: 'Todos los roles'},
                ...roles.map(r => ({value: r.id, label: r.nombre}))
              ]"
              :error="form.errors.rol_id"
            />

            <div class="flex items-center gap-3">
              <input 
                v-model="form.activo" 
                type="checkbox" 
                id="activo"
                class="w-4 h-4 text-blue-600 rounded"
              >
              <label for="activo" class="text-sm font-medium text-theme-primary cursor-pointer">
                Menú activo
              </label>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.menus.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button 
                type="submit" 
                variant="primary"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Creando...' : 'Crear Menú' }}
              </Button>
            </div>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

defineProps<{
  roles: any[];
}>();

const form = useForm({
  nombre: '',
  ruta: '',
  orden: 1,
  rol_id: null,
  activo: true
});

const submit = () => {
  form.post(route('admin.menus.store'));
};
</script>
