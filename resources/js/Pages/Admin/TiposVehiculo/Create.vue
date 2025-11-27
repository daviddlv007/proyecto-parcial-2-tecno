<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Tipo de Vehículo</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo tipo de vehículo</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Input
                v-model="form.nombre"
                label="Nombre"
                placeholder="Ej: Automóvil, Motocicleta..."
                required
                :error="form.errors.nombre"
              />

              <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea
                  v-model="form.descripcion"
                  rows="3"
                  class="textarea"
                  placeholder="Descripción del tipo de vehículo..."
                ></textarea>
                <p v-if="form.errors.descripcion" class="form-error">
                  {{ form.errors.descripcion }}
                </p>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.tipos-vehiculo.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Tipo' }}
              </Button>
            </div>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';

const form = useForm({
  nombre: '',
  descripcion: ''
});

const submit = () => {
  form.post(route('admin.tipos-vehiculo.store'));
};
</script>
