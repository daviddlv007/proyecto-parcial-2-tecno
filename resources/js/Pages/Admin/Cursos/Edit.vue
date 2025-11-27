<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Formulario de edición del curso -->
        <div class="page-header mb-6">
          <h1 class="page-title">Editar Curso</h1>
          <p class="page-subtitle">Actualizar información del curso</p>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <Input
              v-model="form.nombre"
              label="Nombre del Curso"
              required
              :error="form.errors.nombre"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Select
                v-model="form.tipo_curso_id"
                label="Tipo de Curso"
                :options="tiposCurso.map(t => ({ value: t.id, label: t.nombre }))"
                required
                :error="form.errors.tipo_curso_id"
              />

              <Select
                v-model="form.instructor_id"
                label="Instructor"
                :options="instructores.map(i => ({ value: i.id, label: `${i.nombre} ${i.apellido}` }))"
                required
                :error="form.errors.instructor_id"
              />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Input
                v-model="form.capacidad_maxima"
                type="number"
                label="Capacidad Máxima"
                required
                :error="form.errors.capacidad_maxima"
              />

              <Input
                v-model="form.precio"
                type="number"
                step="0.01"
                label="Precio (Bs.)"
                placeholder="1500.00"
                required
                :error="form.errors.precio"
              />
            </div>

            <Select
              v-model="form.estado_curso"
              label="Estado del Curso"
              :options="[
                { value: 'activo', label: 'Activo' },
                { value: 'inactivo', label: 'Inactivo' },
                { value: 'completo', label: 'Completo' },
              ]"
              required
              :error="form.errors.estado_curso"
            />

            <div class="form-actions">
              <Link :href="route('admin.cursos.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button variant="primary" type="submit" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Curso' }}
              </Button>
            </div>
          </form>
        </Card>

        <!-- Ediciones del Curso -->
        <Card class="p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-bold text-theme-primary">Ediciones del Curso</h3>
              <p class="text-sm text-theme-tertiary mt-1">Gestiona las fechas específicas, cupos y horarios de este curso</p>
            </div>
            <Link :href="route('admin.curso-ediciones.create', { curso: curso.id })">
              <Button variant="primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nueva Edición
              </Button>
            </Link>
          </div>

          <div v-if="curso.ediciones && curso.ediciones.length > 0" class="space-y-3">
            <Card v-for="edicion in curso.ediciones" :key="edicion.id" variant="elevated" class="p-4">
              <div class="flex items-center justify-between">
                <div class="flex-1 space-y-2">
                  <div class="flex items-center gap-3">
                    <Badge :variant="getEstadoEdicionVariant(edicion.estado_edicion)">
                      {{ edicion.estado_edicion }}
                    </Badge>
                    <span class="text-sm text-theme-tertiary">
                      {{ new Date(edicion.fecha_inicio).toLocaleDateString() }} - {{ new Date(edicion.fecha_fin).toLocaleDateString() }}
                    </span>
                  </div>
                  <div class="flex items-center gap-4 text-sm">
                    <span class="text-theme-secondary">
                      Cupo: {{ edicion.cupo_disponible }}/{{ edicion.cupo_total }}
                    </span>
                    <span class="text-theme-secondary">
                      Precio: Bs. {{ Number(edicion.precio_final || 0).toFixed(2) }}
                    </span>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <Link :href="route('admin.curso-ediciones.edit', edicion.id)">
                    <Button variant="ghost" size="sm">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                      </svg>
                    </Button>
                  </Link>
                </div>
              </div>
            </Card>
          </div>
          <div v-else class="text-center py-8 bg-theme-secondary rounded-lg">
            <p class="text-theme-tertiary">No hay ediciones creadas para este curso.</p>
            <p class="text-sm text-theme-tertiary mt-1">Crea una nueva edición para comenzar a inscribir alumnos.</p>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps<{
  curso: any;
  tiposCurso: any[];
  instructores: any[];
}>();

const form = useForm({
  nombre: props.curso.nombre,
  tipo_curso_id: props.curso.tipo_curso_id,
  instructor_id: props.curso.instructor_id,
  capacidad_maxima: props.curso.capacidad_maxima,
  precio: props.curso.precio || 0,
  estado_curso: props.curso.estado_curso,
});

const submit = () => {
  form.put(route('admin.cursos.update', props.curso.id));
};

const getEstadoEdicionVariant = (estado: string) => {
  const variants = {
    'programada': 'info',
    'en_curso': 'success',
    'finalizada': 'neutral',
    'cancelada': 'error'
  };
  return variants[estado] || 'neutral';
};
</script>
