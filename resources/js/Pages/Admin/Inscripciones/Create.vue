<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header mb-6">
          <h1 class="page-title">Nueva Inscripción</h1>
          <p class="page-subtitle">Registrar nueva inscripción de alumno a curso</p>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <Select 
              v-model="form.alumno_id" 
              label="Alumno" 
              :options="alumnos.map(a => ({ value: a.id, label: `${a.nombre} ${a.apellido}` }))" 
              required 
              :error="form.errors.alumno_id"
            />

            <div class="space-y-2">
              <Select 
                v-model="form.curso_edicion_id" 
                label="Edición de Curso" 
                :options="ediciones.map(e => ({ 
                    value: e.id, 
                    label: `${(e.curso_nombre || (e.curso && e.curso.nombre) || '—')} - ${formatDate(e.fecha_inicio)} a ${formatDate(e.fecha_fin)} (${e.cupo_disponible}/${e.cupo_total} cupos)` 
                  }))" 
                required 
                :error="form.errors.curso_edicion_id"
              />
              <p class="text-xs text-theme-tertiary">Selecciona una edición específica del curso con fechas y cupos definidos</p>
            </div>

            <Select 
              v-model="form.plan_pago_id" 
              label="Plan de Pago" 
              :options="planesPago.map(p => ({ value: p.id, label: `${p.nombre} (${p.numero_cuotas} cuotas)` }))" 
              required 
              :error="form.errors.plan_pago_id"
            />

            <Select 
              v-model="form.estado_inscripcion" 
              label="Estado" 
              :options="[
                {value:'pendiente',label:'Pendiente'},
                {value:'activa',label:'Activa'},
                {value:'completada',label:'Completada'},
                {value:'cancelada',label:'Cancelada'}
              ]" 
              required 
              :error="form.errors.estado_inscripcion"
            />

            <Card v-if="selectedEdicion" variant="elevated" class="p-4">
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-theme-secondary">Curso:</span>
                  <span class="font-medium text-theme-primary">{{ selectedEdicion.curso_nombre }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-theme-secondary">Periodo:</span>
                  <span class="text-sm text-theme-primary">{{ formatDate(selectedEdicion.fecha_inicio) }} - {{ formatDate(selectedEdicion.fecha_fin) }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-theme-secondary">Cupos disponibles:</span>
                  <Badge :variant="selectedEdicion.cupo_disponible > 0 ? 'success' : 'error'">
                    {{ selectedEdicion.cupo_disponible }}/{{ selectedEdicion.cupo_total }}
                  </Badge>
                </div>
                <div class="border-t border-theme pt-3">
                  <div class="flex items-center justify-between">
                    <span class="text-base font-medium text-theme-secondary">Monto Total:</span>
                    <span class="text-2xl font-bold text-theme-primary">Bs. {{ Number(selectedEdicion.precio_final || 0).toFixed(2) }}</span>
                  </div>
                </div>
              </div>
            </Card>

            <div class="form-actions">
              <Link :href="route('admin.inscripciones.index')">
                <Button variant="secondary" type="button">Cancelar</Button>
              </Link>
              <Button variant="primary" type="submit" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Inscripción' }}
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
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps<{ 
  alumnos: any[];
  ediciones: any[];
  planesPago: any[];
}>();

const form = useForm({ 
  alumno_id: '', 
  curso_edicion_id: '', 
  plan_pago_id: '', 
  estado_inscripcion: 'pendiente'
});

const selectedEdicion = ref(null);

watch(() => form.curso_edicion_id, (newEdicionId) => {
  selectedEdicion.value = props.ediciones.find(e => e.id == newEdicionId);
});

const formatDate = (date: string) => {
  if (!date) return '-';
  return new Date(date).toLocaleDateString('es-BO', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};

const submit = () => form.post(route('admin.inscripciones.store'));
</script>
