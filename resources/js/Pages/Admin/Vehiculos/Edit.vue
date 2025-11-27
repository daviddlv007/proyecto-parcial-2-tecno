<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="page-title mb-4">Editar Vehículo</h1>
        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-4">
            <Input v-model="form.placa" label="Placa" required :error="form.errors.placa" />
            <Select 
              v-model="form.tipo_vehiculo_id" 
              :options="tipos.map(t => ({ value: t.id, label: t.nombre }))" 
              label="Tipo de Vehículo" 
              :error="form.errors.tipo_vehiculo_id" 
            />
            <Input v-model="form.marca" label="Marca" :error="form.errors.marca" />
            <Input v-model="form.modelo" label="Modelo" :error="form.errors.modelo" />
            <Input v-model.number="form.anio" label="Año" type="number" :error="form.errors.anio" />
            <Select 
              v-model="form.estado_vehiculo" 
              label="Estado"
              :options="[
                {value:'disponible',label:'Disponible'},
                {value:'en uso',label:'En Uso'},
                {value:'en mantenimiento',label:'En Mantenimiento'},
                {value:'fuera de servicio',label:'Fuera de Servicio'}
              ]"
              :error="form.errors.estado_vehiculo"
            />

            <div class="flex justify-end gap-3">
              <Link :href="route('admin.vehiculos.index')">
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

const props = defineProps<{
  vehiculo: any;
  tipos: any[];
}>();

const form = useForm({
  placa: props.vehiculo.placa || '',
  tipo_vehiculo_id: props.vehiculo.tipo_vehiculo_id || null,
  marca: props.vehiculo.marca || '',
  modelo: props.vehiculo.modelo || '',
  anio: props.vehiculo.anio || '',
  estado_vehiculo: props.vehiculo.estado_vehiculo || 'disponible'
});

const submit = () => {
  form.put(route('admin.vehiculos.update', props.vehiculo.id));
};
</script>
