<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="page-title mb-4">Editar Tipo de Curso</h1>
        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-4">
            <Input v-model="form.nombre" label="Nombre" required :error="form.errors.nombre" />
            <Input v-model="form.descripcion" label="Descripción" as="textarea" :error="form.errors.descripcion" />
            <Input v-model.number="form.duracion_horas" label="Duración (hrs)" type="number" :error="form.errors.duracion_horas" />
            <Select 
              v-model="form.nivel" 
              label="Nivel"
              :options="[
                {value:'básico',label:'Básico'},
                {value:'intermedio',label:'Intermedio'},
                {value:'avanzado',label:'Avanzado'}
              ]" 
              :error="form.errors.nivel" 
            />

            <div class="flex justify-end gap-3">
              <Link :href="route('admin.tipos-curso.index')">
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

const props = defineProps<{ tipo: any }>();

const form = useForm({
  nombre: props.tipo.nombre || '',
  descripcion: props.tipo.descripcion || '',
  duracion_horas: props.tipo.duracion_horas || null,
  nivel: props.tipo.nivel || ''
});

const submit = () => {
  form.put(route('admin.tipos-curso.update', props.tipo.id));
};
</script>
