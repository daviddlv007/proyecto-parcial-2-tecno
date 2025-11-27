<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Editar Pago</h1>
            <p class="page-subtitle">Modifique los datos del pago</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg mb-4">
                <h3 class="font-semibold text-theme-primary mb-2">Información de la Inscripción</h3>
                <div class="space-y-1 text-sm">
                  <p><span class="text-theme-secondary">Alumno:</span> <span class="font-medium">{{ pago.alumno?.nombre }} {{ pago.alumno?.apellido }}</span></p>
                  <p><span class="text-theme-secondary">Curso:</span> <span class="font-medium">{{ pago.inscripcion?.edicion?.curso?.nombre }}</span></p>
                  <p v-if="pago.inscripcion?.edicion">
                    <span class="text-theme-secondary">Periodo:</span> 
                    <span class="font-medium">{{ formatDate(pago.inscripcion.edicion.fecha_inicio) }} - {{ formatDate(pago.inscripcion.edicion.fecha_fin) }}</span>
                  </p>
                </div>
              </div>

              <Select
                v-model="form.inscripcion_id"
                label="Cambiar Inscripción (opcional)"
                :options="inscripciones.map(i => ({
                  value: i.id,
                  label: `${i.alumno_nombre} - ${(i.curso_nombre || (i.edicion && i.edicion.curso && i.edicion.curso.nombre) || '—')} (${i.fecha_inicio} a ${i.fecha_fin})`
                }))"
                :error="form.errors.inscripcion_id"
              />

              <Select
                v-model="form.metodo_pago_id"
                label="Método de Pago"
                :options="metodosPago.map(m => ({ value: m.id, label: m.nombre }))"
                required
                :error="form.errors.metodo_pago_id"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.fecha"
                  type="date"
                  label="Fecha de Pago"
                  required
                  :error="form.errors.fecha"
                />

                <Input
                  v-model="form.monto"
                  type="number"
                  step="0.01"
                  label="Monto (Bs.)"
                  required
                  :error="form.errors.monto"
                />
              </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.pagos.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Pago' }}
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

const props = defineProps<{
  pago: any;
  inscripciones: any[];
  metodosPago: any[];
}>();

// Format datetime to date string
const formatDate = (date: string) => {
  if (!date) return '-';
  const d = new Date(date);
  return d.toLocaleDateString('es-BO', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};

// Format date for input type="date" (YYYY-MM-DD)
const formatDateForInput = (date: string) => {
  if (!date) return '';
  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

const form = useForm({
  inscripcion_id: props.pago.inscripcion_id || null,
  metodo_pago_id: props.pago.metodo_pago_id || null,
  fecha: formatDateForInput(props.pago.fecha),
  monto: props.pago.monto || 0
});

const submit = () => form.put(route('admin.pagos.update', props.pago.id));
</script>
