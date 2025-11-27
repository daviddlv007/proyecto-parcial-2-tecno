<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <Link :href="route('alumno.cursos.disponibles')">
          <Button variant="ghost" size="sm">← Volver a Cursos Disponibles</Button>
        </Link>
      </div>

      <div class="max-w-4xl mx-auto">
        <Card>
          <h2 class="text-2xl font-bold text-theme-primary mb-6">Inscripción a Curso</h2>
          
          <!-- Info del Curso -->
          <div class="mb-8 p-6 bg-theme-secondary rounded-lg">
            <h3 class="text-xl font-bold text-theme-primary mb-4">{{ cursoData.nombre }}</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-theme-secondary">Tipo:</span>
                <Badge>{{ cursoData.tipo }}</Badge>
              </div>
              <div class="flex justify-between">
                <span class="text-theme-secondary">Fechas:</span>
                <span class="font-medium">{{ cursoData.fecha_inicio }} - {{ cursoData.fecha_fin }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-theme-secondary">Profesor:</span>
                <span class="font-medium">{{ cursoData.profesor }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-theme-secondary">Precio Total:</span>
                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">Bs {{ cursoData.precio }}</span>
              </div>
            </div>

            <div v-if="cursoData.descripcion" class="mt-4 pt-4 border-t border-theme">
              <p class="text-sm text-theme-secondary">{{ cursoData.descripcion }}</p>
            </div>

            <div v-if="cursoData.horarios.length > 0" class="mt-4 pt-4 border-t border-theme">
              <p class="text-sm font-semibold text-theme-primary mb-2">Horarios de Clase:</p>
              <div class="flex flex-wrap gap-2">
                <div v-for="(horario, idx) in cursoData.horarios" :key="idx" class="text-sm bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded">
                  {{ getDiaNombre(horario.dia) }}: {{ horario.hora_inicio }}-{{ horario.hora_fin }}
                </div>
              </div>
            </div>
          </div>

          <form @submit.prevent="submit">
            <div class="space-y-6">
              <div class="form-group">
                <label class="form-label form-label-required">Selecciona tu Plan de Pago</label>
                <div class="space-y-3">
                  <label v-for="plan in planesPago" :key="plan.id" class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all" :class="form.plan_pago_id === plan.id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-theme hover:border-blue-300'">
                    <input v-model="form.plan_pago_id" type="radio" :value="plan.id" class="mt-1 mr-3" required />
                    <div class="flex-1">
                      <p class="font-semibold text-theme-primary">{{ plan.nombre }}</p>
                      <p class="text-sm text-theme-secondary mt-1">{{ plan.numero_cuotas }} cuota{{ plan.numero_cuotas > 1 ? 's' : '' }} de Bs {{ (cursoData.precio / plan.numero_cuotas).toFixed(2) }}</p>
                      <p class="text-xs text-theme-tertiary mt-1">Periodicidad: {{ plan.periodicidad }}</p>
                    </div>
                  </label>
                </div>
                <p v-if="form.errors.plan_pago_id" class="form-error">{{ form.errors.plan_pago_id }}</p>
              </div>

              <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                  <strong>Importante:</strong> Al inscribirte, aceptas el plan de pago seleccionado. Deberás realizar los pagos según las fechas establecidas.
                </p>
              </div>

              <div class="form-actions">
                <Link :href="route('alumno.cursos.disponibles')">
                  <Button variant="ghost">Cancelar</Button>
                </Link>
                <Button type="submit" variant="primary" :disabled="form.processing || !form.plan_pago_id">
                  {{ form.processing ? 'Procesando...' : 'Confirmar Inscripción' }}
                </Button>
              </div>
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
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps<{
  cursoData: any;
  planesPago: any[];
}>();

const form = useForm({
  curso_edicion_id: props.cursoData.edicion_id,
  plan_pago_id: null as number | null,
});

const submit = () => {
  form.post(route('alumno.cursos.store-inscripcion'));
};

const getDiaNombre = (dia: number) => {
  const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  return dias[dia] || 'N/A';
};
</script>
