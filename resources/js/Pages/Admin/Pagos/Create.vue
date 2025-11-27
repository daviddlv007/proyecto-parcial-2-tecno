<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Registrar Nuevo Pago</h1>
            <p class="page-subtitle">Complete el formulario para registrar un pago</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Select
                v-model="form.inscripcion_id"
                label="Inscripción"
                :options="inscripciones.map(i => ({
                  value: i.id,
                  label: `${i.alumno_nombre} - ${(i.curso_nombre || (i.edicion && i.edicion.curso && i.edicion.curso.nombre) || '—')} (${i.fecha_inicio} a ${i.fecha_fin})`
                }))"
                required
                :error="form.errors.inscripcion_id"
              />

              <Card v-if="selectedInscripcion" variant="elevated" class="p-4 bg-blue-50 dark:bg-blue-900/20">
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-theme-secondary">Alumno:</span>
                    <span class="font-medium text-theme-primary">{{ selectedInscripcion.alumno_nombre }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-theme-secondary">Curso:</span>
                    <span class="font-medium text-theme-primary">{{ selectedInscripcion.curso_nombre }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-theme-secondary">Periodo:</span>
                    <span class="font-medium text-theme-primary">{{ selectedInscripcion.fecha_inicio }} - {{ selectedInscripcion.fecha_fin }}</span>
                  </div>
                  <div class="flex justify-between text-sm border-t border-blue-200 dark:border-blue-800 pt-2">
                    <span class="text-theme-secondary">Monto Total:</span>
                    <span class="font-bold text-theme-primary">Bs. {{ Number(selectedInscripcion.monto_total || 0).toFixed(2) }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-theme-secondary">Saldo Pendiente:</span>
                    <span class="font-bold text-red-600">Bs. {{ Number(selectedInscripcion.saldo_pendiente || 0).toFixed(2) }}</span>
                  </div>
                </div>
              </Card>

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
                  placeholder="1500.00"
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
                {{ form.processing ? 'Registrando...' : 'Registrar Pago' }}
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
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const props = defineProps<{
  inscripciones: any[];
  metodosPago: any[];
}>();

const form = useForm({
  inscripcion_id: '',
  metodo_pago_id: '',
  fecha: new Date().toISOString().split('T')[0],
  monto: 0
});

const selectedInscripcion = ref(null);

watch(() => form.inscripcion_id, (newId) => {
  selectedInscripcion.value = props.inscripciones.find(i => i.id == newId);
});

const submit = () => form.post(route('admin.pagos.store'));
</script>
