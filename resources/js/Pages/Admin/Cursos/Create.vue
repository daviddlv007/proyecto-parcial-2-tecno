<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nuevo Curso</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo curso</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <!-- Nombre -->
              <Input
                v-model="form.nombre"
                label="Nombre del Curso"
                placeholder="Ej: Curso de Conducción Básica"
                :error="validationErrors.nombre || form.errors.nombre"
              />

              <!-- Tipo de Curso -->
              <Select
                v-model="form.tipo_curso_id"
                label="Tipo de Curso"
                :options="tiposCurso.map(t => ({ value: t.id, label: t.nombre }))"
                :error="validationErrors.tipo_curso_id || form.errors.tipo_curso_id"
              />

              <!-- Descripción -->
              <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea
                  v-model="form.descripcion"
                  rows="4"
                  class="textarea"
                  placeholder="Descripción detallada del curso..."
                ></textarea>
              </div>

              <!-- Instructor -->
              <Select
                v-model="form.instructor_id"
                label="Instructor"
                :options="instructores.map(i => ({ value: i.id, label: `${i.nombre} ${i.apellido}` }))"
                :error="validationErrors.instructor_id || form.errors.instructor_id"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Capacidad Máxima -->
                <Input
                  v-model="form.capacidad_maxima"
                  type="number"
                  label="Capacidad Máxima"
                  placeholder="20"
                  :error="validationErrors.capacidad_maxima || form.errors.capacidad_maxima"
                />

                <!-- Precio -->
                <Input
                  v-model="form.precio"
                  type="number"
                  label="Precio (Bs.)"
                  placeholder="1500.00"
                  hint="Monto en bolivianos"
                  :error="validationErrors.precio || form.errors.precio"
                />
              </div>

              <!-- Estado -->
              <Select
                v-model="form.estado_curso"
                label="Estado del Curso"
                :options="[
                  { value: 'activo', label: 'Activo' },
                  { value: 'inactivo', label: 'Inactivo' },
                  { value: 'completo', label: 'Completo' }
                ]"
                :error="validationErrors.estado_curso || form.errors.estado_curso"
              />

              <!-- Info Notice -->
              <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start gap-3">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Información importante</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                      Este formulario crea la plantilla base del curso. Los horarios, fechas específicas y cupos se configuran al crear <strong>Ediciones del Curso</strong>.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.cursos.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                <svg v-if="form.processing" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Creando...' : 'Crear Curso' }}
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

const props = defineProps<{
  tiposCurso: any[];
  instructores: any[];
}>();

const form = useForm({
  nombre: '',
  tipo_curso_id: '',
  descripcion: '',
  instructor_id: '',
  capacidad_maxima: 20,
  precio: 0,
  estado_curso: 'activo'
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();
  
  const isValid = validate(form, {
    nombre: { required: true, maxLength: 200 },
    tipo_curso_id: { required: true },
    instructor_id: { required: true },
    capacidad_maxima: { required: true, numeric: true, min: 1 },
    precio: { required: true, numeric: true, min: 0 },
    estado_curso: { required: true },
  });

  if (!isValid) {
    return;
  }

  form.post(route('admin.cursos.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>
