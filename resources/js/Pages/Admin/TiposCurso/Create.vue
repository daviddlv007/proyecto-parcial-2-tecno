<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Tipo de Curso</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo tipo de curso</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Input
                v-model="form.nombre"
                label="Nombre"
                placeholder="Ej: Conducción Básica, Conducción Avanzada..."
                required
                :error="form.errors.nombre"
              />

              <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea
                  v-model="form.descripcion"
                  rows="3"
                  class="textarea"
                  placeholder="Descripción del tipo de curso..."
                ></textarea>
                <p v-if="form.errors.descripcion" class="form-error">
                  {{ form.errors.descripcion }}
                </p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.duracion_horas"
                  type="number"
                  step="0.5"
                  label="Duración (horas)"
                  placeholder="40"
                  :error="form.errors.duracion_horas"
                />

                <Select
                  v-model="form.nivel"
                  label="Nivel"
                  :options="[
                    { value: 'Básico', label: 'Básico' },
                    { value: 'Intermedio', label: 'Intermedio' },
                    { value: 'Avanzado', label: 'Avanzado' }
                  ]"
                  :error="form.errors.nivel"
                />
              </div>

              <Select
                v-model="form.estado_curso"
                label="Estado"
                :options="[
                  { value: 'activo', label: 'Activo' },
                  { value: 'inactivo', label: 'Inactivo' }
                ]"
                :error="form.errors.estado_curso"
              />
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.tipos-curso.index')">
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
import Select from '@/Components/UI/Select.vue';

const form = useForm({
  nombre: '',
  descripcion: '',
  duracion_horas: null as number | null,
  nivel: '',
  estado_curso: 'activo'
});

const submit = () => {
  form.post(route('admin.tipos-curso.store'));
};
</script>
