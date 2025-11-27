<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="page-title mb-4">Editar Método de Pago</h1>
        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-4">
            <Input v-model="form.nombre" label="Nombre" required :error="form.errors.nombre" />
            <Input v-model="form.descripcion" label="Descripción" as="textarea" :error="form.errors.descripcion" />

            <div class="flex justify-end gap-3">
              <Link :href="route('admin.metodos-pago.index')">
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
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps<{ metodo: any }>();

const form = useForm({
  nombre: props.metodo.nombre || '',
  descripcion: props.metodo.descripcion || ''
});

const submit = () => {
  form.put(route('admin.metodos-pago.update', props.metodo.id));
};
</script>
