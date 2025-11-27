<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Plan de Pago</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo plan de pago</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Input
                v-model="form.nombre"
                label="Nombre"
                placeholder="Ej: Plan Quincenal, Plan Mensual..."
                required
                :error="form.errors.nombre"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.numero_cuotas"
                  type="number"
                  min="1"
                  max="24"
                  label="Número de Cuotas"
                  required
                  :error="form.errors.numero_cuotas"
                />

                <Select
                  v-model="form.periodicidad"
                  label="Periodicidad"
                  :options="[
                    { value: 'semanal', label: 'Semanal' },
                    { value: 'quincenal', label: 'Quincenal' },
                    { value: 'mensual', label: 'Mensual' }
                  ]"
                  @change="calcularDiasIntervalo"
                  required
                  :error="form.errors.periodicidad"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.dias_intervalo"
                  type="number"
                  label="Días Intervalo (auto-calculado)"
                  disabled
                  hint="Se calcula automáticamente según la periodicidad"
                  :error="form.errors.dias_intervalo"
                />

                <Input
                  v-model="form.dias_maximo_total"
                  type="number"
                  min="1"
                  label="Días Máximo Total"
                  required
                  :error="form.errors.dias_maximo_total"
                />
              </div>

              <div class="flex items-center gap-3">
                <input
                  v-model="form.activo"
                  type="checkbox"
                  id="activo"
                  class="w-4 h-4 text-blue-600 rounded"
                >
                <label for="activo" class="text-sm font-medium text-theme-primary cursor-pointer">
                  Plan activo
                </label>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.planes-pago.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Plan' }}
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
import Select from '@/Components/UI/Select.vue';

const form = useForm({
  nombre: '',
  numero_cuotas: 1,
  periodicidad: '',
  dias_intervalo: null as number | null,
  dias_maximo_total: null as number | null,
  activo: true
});

const calcularDiasIntervalo = () => {
  const mapping: Record<string, number> = {
    'semanal': 7,
    'quincenal': 15,
    'mensual': 30
  };
  form.dias_intervalo = mapping[form.periodicidad] || null;
};

const submit = () => {
  form.post(route('admin.planes-pago.store'));
};
</script>
