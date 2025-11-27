<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nuevo Vehículo</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo vehículo</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Input
                v-model="form.placa"
                label="Placa"
                placeholder="ABC-1234"
                :error="validationErrors.placa || form.errors.placa"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Select
                  v-model="form.tipo_vehiculo_id"
                  label="Tipo de Vehículo"
                  :options="tiposVehiculo.map(t => ({ value: t.id, label: t.nombre }))"
                  :error="validationErrors.tipo_vehiculo_id || form.errors.tipo_vehiculo_id"
                />

                <Select
                  v-model="form.estado_vehiculo"
                  label="Estado"
                  :options="[
                    { value: 'disponible', label: 'Disponible' },
                    { value: 'en uso', label: 'En Uso' },
                    { value: 'en mantenimiento', label: 'En Mantenimiento' },
                    { value: 'fuera de servicio', label: 'Fuera de Servicio' }
                  ]"
                  :error="validationErrors.estado_vehiculo || form.errors.estado_vehiculo"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.marca"
                  label="Marca"
                  placeholder="Toyota"
                  :error="validationErrors.marca || form.errors.marca"
                />

                <Input
                  v-model="form.modelo"
                  label="Modelo"
                  placeholder="Corolla"
                  :error="validationErrors.modelo || form.errors.modelo"
                />
              </div>

              <Input
                v-model="form.anio"
                type="number"
                label="Año"
                placeholder="2024"
                :error="validationErrors.anio || form.errors.anio"
              />
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.vehiculos.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Vehículo' }}
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
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import { useValidation } from '@/composables/useValidation';

defineProps<{ tiposVehiculo: any[] }>();

const form = useForm({
  placa: '',
  tipo_vehiculo_id: '',
  marca: '',
  modelo: '',
  anio: new Date().getFullYear(),
  estado_vehiculo: 'disponible'
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();
  
  const isValid = validate(form, {
    placa: { required: true },
    tipo_vehiculo_id: { required: true },
    marca: { required: true, maxLength: 100 },
    modelo: { required: true, maxLength: 100 },
    anio: { required: true, numeric: true, min: 1900, max: new Date().getFullYear() + 1 },
    estado_vehiculo: { required: true },
  });

  if (!isValid) {
    return;
  }

  form.post(route('admin.vehiculos.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>
