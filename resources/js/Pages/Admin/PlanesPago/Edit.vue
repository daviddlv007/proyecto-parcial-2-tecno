<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="page-title mb-4">Editar Plan de Pago</h1>
        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-4">
            <Input v-model="form.nombre" label="Nombre" required :error="form.errors.nombre" />
            <Input v-model.number="form.numero_cuotas" label="Número de Cuotas" type="number" :error="form.errors.numero_cuotas" />
            <Select 
              v-model="form.periodicidad" 
              label="Periodicidad"
              :options="[
                {value: 'semanal', label: 'Semanal'},
                {value: 'quincenal', label: 'Quincenal'},
                {value: 'mensual', label: 'Mensual'}
              ]"
              :error="form.errors.periodicidad"
            />
            <Input v-model.number="form.dias_maximo_total" label="Días Máx Total" type="number" :error="form.errors.dias_maximo_total" />
            <div class="flex items-center gap-3">
              <input v-model="form.activo" type="checkbox" id="activo" class="w-4 h-4 text-blue-600 rounded" />
              <label for="activo" class="text-sm font-medium text-theme-primary cursor-pointer">Plan activo</label>
            </div>

            <div class="flex justify-end gap-3">
              <Link :href="route('admin.planes-pago.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary">Guardar</Button>
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
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps<{ plan: any }>();

const form = useForm({
  nombre: props.plan.nombre || '',
  numero_cuotas: props.plan.numero_cuotas || 1,
  periodicidad: props.plan.periodicidad || '',
  dias_maximo_total: props.plan.dias_maximo_total || 0,
  activo: props.plan.activo ?? true
});

const submit = () => {
  form.put(route('admin.planes-pago.update', props.plan.id));
};
</script>
