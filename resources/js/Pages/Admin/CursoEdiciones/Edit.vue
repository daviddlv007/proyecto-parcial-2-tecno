<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header mb-6">
          <div>
            <h1 class="page-title">Editar Edición de Curso</h1>
            <p class="page-subtitle">Actualiza los datos de esta edición</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Curso -->
            <Select
              v-model="form.curso_id"
              label="Curso"
              :options="cursos.map(c => ({ value: c.id, label: `${c.nombre} (${c.tipo})` }))"
              required
              :error="form.errors.curso_id"
            />

            <!-- Fechas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Input
                v-model="form.fecha_inicio"
                type="date"
                label="Fecha Inicio"
                required
                :error="form.errors.fecha_inicio"
              />
              <Input
                v-model="form.fecha_fin"
                type="date"
                label="Fecha Fin"
                required
                :error="form.errors.fecha_fin"
              />
            </div>

            <!-- Cupo y Precio -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Input
                  v-model="form.cupo_total"
                  type="number"
                  label="Cupo Total"
                  min="1"
                  required
                  :error="form.errors.cupo_total"
                />
                <p class="text-xs text-theme-secondary mt-1">
                  Cupo disponible actual: {{ edicion.cupo_disponible }}
                </p>
              </div>
              <Input
                v-model="form.precio_final"
                type="number"
                label="Precio Final (Bs.)"
                step="0.01"
                min="0"
                required
                :error="form.errors.precio_final"
              />
            </div>

            <!-- Estado -->
            <Select
              v-model="form.estado_edicion"
              label="Estado"
              :options="[
                { value: 'programada', label: 'Programada' },
                { value: 'en_curso', label: 'En Curso' },
                { value: 'finalizada', label: 'Finalizada' },
                { value: 'cancelada', label: 'Cancelada' }
              ]"
              required
              :error="form.errors.estado_edicion"
            />

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
              <p class="text-sm text-blue-800 dark:text-blue-200">
                <strong>Nota:</strong> Esta edición tiene {{ edicion.inscritos || 0 }} inscripción(es). Ten cuidado al modificar fechas o cupo.
              </p>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.curso-ediciones.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
              </Button>
            </div>
          </form>
        </Card>

        <!-- Horarios Section -->
        <Card class="mt-6">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-semibold text-theme-primary">Horarios de la Edición</h2>
              <Button @click="showAddHorario = true" variant="primary" size="sm">
                + Agregar Horario
              </Button>
            </div>

            <div v-if="edicion.horarios && edicion.horarios.length > 0" class="space-y-2">
              <div v-for="horario in edicion.horarios" :key="horario.id" 
                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center space-x-4">
                  <Badge>{{ getDiaNombre(horario.dia_semana) }}</Badge>
                  <span class="text-sm text-theme-secondary">
                    {{ horario.hora_inicio }} - {{ horario.hora_fin }}
                  </span>
                </div>
                <Button 
                  @click="deleteHorario(horario.id)" 
                  variant="danger" 
                  size="sm"
                  type="button"
                >
                  Eliminar
                </Button>
              </div>
            </div>
            <div v-else class="text-center py-8 text-theme-tertiary">
              No hay horarios configurados para esta edición
            </div>

            <!-- Add Horario Modal/Form -->
            <Card v-if="showAddHorario" variant="elevated" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20">
              <form @submit.prevent="submitHorario" class="space-y-4">
                <h3 class="font-semibold text-theme-primary mb-2">Nuevo Horario</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <Select
                    v-model="horarioForm.dia_semana"
                    label="Día"
                    :options="[
                      { value: 1, label: 'Lunes' },
                      { value: 2, label: 'Martes' },
                      { value: 3, label: 'Miércoles' },
                      { value: 4, label: 'Jueves' },
                      { value: 5, label: 'Viernes' },
                      { value: 6, label: 'Sábado' },
                      { value: 7, label: 'Domingo' }
                    ]"
                    required
                  />
                  <Input
                    v-model="horarioForm.hora_inicio"
                    type="time"
                    label="Hora Inicio"
                    required
                  />
                  <Input
                    v-model="horarioForm.hora_fin"
                    type="time"
                    label="Hora Fin"
                    required
                  />
                </div>
                <div class="flex justify-end space-x-2">
                  <Button @click="showAddHorario = false" variant="secondary" size="sm" type="button">
                    Cancelar
                  </Button>
                  <Button type="submit" variant="primary" size="sm">
                    Guardar Horario
                  </Button>
                </div>
              </form>
            </Card>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps<{
  edicion: any;
  cursos: any[];
}>();

const form = useForm({
  curso_id: props.edicion.curso_id,
  fecha_inicio: props.edicion.fecha_inicio,
  fecha_fin: props.edicion.fecha_fin,
  cupo_total: props.edicion.cupo_total,
  precio_final: props.edicion.precio_final,
  estado_edicion: props.edicion.estado_edicion
});

const showAddHorario = ref(false);
const horarioForm = ref({
  dia_semana: '',
  hora_inicio: '',
  hora_fin: ''
});

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || '';
};

const submit = () => {
  form.put(route('admin.curso-ediciones.update', props.edicion.id));
};

const submitHorario = () => {
  router.post(
    route('admin.curso-ediciones.horarios.store', props.edicion.id),
    horarioForm.value,
    {
      onSuccess: () => {
        horarioForm.value = { dia_semana: '', hora_inicio: '', hora_fin: '' };
        showAddHorario.value = false;
      }
    }
  );
};

const deleteHorario = (horarioId: number) => {
  if (confirm('¿Eliminar este horario?')) {
    router.delete(route('admin.curso-ediciones.horarios.destroy', [props.edicion.id, horarioId]));
  }
};
</script>
