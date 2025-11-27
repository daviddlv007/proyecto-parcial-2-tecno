<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header mb-6">
          <h1 class="page-title">Nuevo Método de Pago</h1>
          <p class="page-subtitle">Crea una nueva forma de pago</p>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <Input
              v-model="form.nombre"
              label="Nombre"
              placeholder="Ej: Efectivo, Tarjeta de crédito..."
              required
              :error="form.errors.nombre"
            />

            <div class="form-group">
              <label class="form-label">Descripción</label>
              <textarea 
                v-model="form.descripcion" 
                rows="3" 
                class="textarea"
                placeholder="Describe las características de este método de pago..."
              ></textarea>
              <p v-if="form.errors.descripcion" class="text-red-500 text-sm mt-1">
                {{ form.errors.descripcion }}
              </p>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.metodos-pago.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button variant="primary" type="submit" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Método de Pago' }}
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
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const form = useForm({
  nombre: '',
  descripcion: ''
});

const submit = () => {
  form.post(route('admin.metodos-pago.store'));
};
</script>
