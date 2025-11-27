<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header mb-6">
          <div>
            <h1 class="page-title">Nueva Edición de Curso</h1>
            <p class="page-subtitle">Crea una nueva edición con fechas y cupos específicos</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Seleccionar Curso -->
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
              <Input
                v-model="form.cupo_total"
                type="number"
                label="Cupo Total"
                min="1"
                required
                :error="form.errors.cupo_total"
              />
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

            <div class="form-actions">
              <Link :href="route('admin.curso-ediciones.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Guardando...' : 'Crear Edición' }}
              </Button>
            </div>
          </form>
        </Card>

        <!-- Horarios Section (pre-create) -->
        <Card class="mt-6">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-semibold text-theme-primary">Horarios (Opcional)</h2>
              <Button @click="showAddHorario = true" variant="primary" size="sm">
                + Agregar Horario
              </Button>
            </div>

            <p class="text-sm text-theme-secondary mb-4">
              Puedes agregar horarios ahora o después de crear la edición.
            </p>

            <div v-if="horarios.length > 0" class="space-y-2">
              <div v-for="(horario, index) in horarios" :key="index" 
                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center space-x-4">
                  <Badge>{{ getDiaNombre(horario.dia_semana) }}</Badge>
                  <span class="text-sm text-theme-secondary">
                    {{ horario.hora_inicio }} - {{ horario.hora_fin }}
                  </span>
                </div>
                <Button 
                  @click="removeHorario(index)" 
                  variant="danger" 
                  size="sm"
                  type="button"
                >
                  Quitar
                </Button>
              </div>
            </div>
            <div v-else class="text-center py-8 text-theme-tertiary">
              No hay horarios agregados
            </div>

            <!-- Add Horario Form -->
            <Card v-if="showAddHorario" variant="elevated" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20">
              <form @submit.prevent="addHorario" class="space-y-4">
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
                    Agregar
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
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps<{
  cursos: any[];
}>();

const form = useForm({
  curso_id: '',
  fecha_inicio: '',
  fecha_fin: '',
  cupo_total: 10,
  precio_final: 0,
  estado_edicion: 'programada',
  horarios: [] as any[]
});

const showAddHorario = ref(false);
const horarios = ref<any[]>([]);
const horarioForm = ref({
  dia_semana: '',
  hora_inicio: '',
  hora_fin: ''
});

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || '';
};

const addHorario = () => {
  horarios.value.push({ ...horarioForm.value });
  horarioForm.value = { dia_semana: '', hora_inicio: '', hora_fin: '' };
  showAddHorario.value = false;
};

const removeHorario = (index: number) => {
  horarios.value.splice(index, 1);
};

const submit = () => {
  form.horarios = horarios.value;
  form.post(route('admin.curso-ediciones.store'));
};
</script>
