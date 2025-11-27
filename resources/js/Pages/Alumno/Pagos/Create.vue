<template>
  <AuthenticatedLayout>
    <div class="page-container">
      <div class="page-header">
        <Link :href="route('alumno.pagos')">
          <Button variant="ghost" size="sm">← Volver</Button>
        </Link>
      </div>

      <div class="max-w-2xl mx-auto">
        <Card>
          <h2 class="text-2xl font-bold text-theme-primary mb-6">Realizar Pago</h2>
          
          <!-- Info del curso -->
          <div class="mb-6 p-4 bg-theme-secondary rounded-lg">
            <h3 class="font-semibold text-theme-primary">{{ pagoInfo.curso }}</h3>
            <div class="mt-2 space-y-1">
              <p class="text-sm text-theme-secondary">Saldo Pendiente: <span class="font-bold text-orange-600 dark:text-orange-400">Bs {{ pagoInfo.saldo_pendiente }}</span></p>
              <p class="text-sm text-theme-secondary">Cuota Sugerida: <span class="font-medium">Bs {{ pagoInfo.monto_cuota }}</span></p>
            </div>
          </div>

          <form @submit.prevent="submit">
            <div class="space-y-4">
              <div class="form-group">
                <label class="form-label form-label-required">Monto a Pagar</label>
                <input v-model="form.monto" type="number" step="0.01" min="0.01" :max="pagoInfo.saldo_pendiente" class="input" required />
                <p v-if="form.errors.monto" class="form-error">{{ form.errors.monto }}</p>
              </div>

              <div class="form-group">
                <label class="form-label form-label-required">Método de Pago</label>
                <select v-model="form.metodo_pago_id" class="select" required>
                  <option value="">Seleccionar método</option>
                  <option v-for="metodo in metodosPago" :key="metodo.id" :value="metodo.id">{{ metodo.nombre }}</option>
                </select>
                <p v-if="form.errors.metodo_pago_id" class="form-error">{{ form.errors.metodo_pago_id }}</p>
              </div>

              <!-- Botón para pagar con QR -->
              <div class="form-group">
                <Button type="button" @click="pagarConQR" variant="secondary" class="w-full">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                  </svg>
                  Pagar con QR
                </Button>
              </div>

              <div class="form-actions">
                <Link :href="route('alumno.pagos')">
                  <Button variant="ghost">Cancelar</Button>
                </Link>
                <Button type="submit" variant="primary" :disabled="form.processing">
                  {{ form.processing ? 'Procesando...' : 'Confirmar Pago' }}
                </Button>
              </div>
            </div>
          </form>
        </Card>
      </div>

      <!-- Modal QR -->
      <div v-if="mostrarModalQR" class="modal-overlay" @click.self="cerrarModalQR">
        <Card class="max-w-md">
          <div class="text-center">
            <h3 class="text-xl font-bold text-theme-primary mb-4">Escanea el Código QR</h3>
            
            <!-- Loading State -->
            <div v-if="cargandoQR" class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 mb-4">
              <div class="w-48 h-48 mx-auto flex items-center justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-theme-primary"></div>
              </div>
              <p class="text-theme-secondary mt-4">Generando código QR...</p>
            </div>
            
            <!-- QR Code Display -->
            <div v-else-if="qrData" class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 mb-4">
              <div class="w-64 h-64 mx-auto bg-white rounded-lg flex items-center justify-center">
                <img :src="qrData.qr_code" alt="Código QR PagoFácil" class="w-full h-full object-contain" />
              </div>
            </div>

            <div v-if="qrData">
              <p class="text-theme-secondary mb-2">Monto: <span class="font-bold">Bs {{ qrData.monto }}</span></p>
              <p class="text-xs text-theme-tertiary mb-4">Transaction ID: {{ qrData.transaction_id }}</p>
              <p class="text-sm text-theme-secondary mb-6">Escanea el código QR con tu aplicación de banca móvil para completar el pago.</p>
              
              <div class="flex gap-3">
                <Button @click="cerrarModalQR" variant="ghost" class="flex-1">Cancelar</Button>
                <Button @click="verificarPago" variant="primary" class="flex-1">Verificar Pago</Button>
              </div>
            </div>
          </div>
        </Card>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps<{
  pagoInfo: any;
  metodosPago: any[];
}>();

const form = useForm({
  inscripcion_id: props.pagoInfo.inscripcion_id,
  monto: props.pagoInfo.monto_cuota,
  metodo_pago_id: '',
});

const mostrarModalQR = ref(false);
const qrData = ref<any>(null);
const cargandoQR = ref(false);

const submit = () => {
  form.post(route('alumno.pagos.store'));
};

const pagarConQR = async () => {
  if (!form.monto || form.monto <= 0) {
    alert('Por favor ingresa un monto válido');
    return;
  }
  
  cargandoQR.value = true;
  mostrarModalQR.value = true;
  
  try {
    const response = await fetch(route('alumno.pagos.generar-qr'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify({
        inscripcion_id: props.pagoInfo.inscripcion_id,
      }),
    });
    
    const data = await response.json();
    
    if (data.success) {
      qrData.value = data;
    } else {
      alert(data.error || 'Error al generar el código QR');
      cerrarModalQR();
    }
  } catch (error) {
    console.error('Error generando QR:', error);
    alert('Error al generar el código QR. Por favor intenta nuevamente.');
    cerrarModalQR();
  } finally {
    cargandoQR.value = false;
  }
};

const cerrarModalQR = () => {
  mostrarModalQR.value = false;
  qrData.value = null;
};

const verificarPago = () => {
  // Recargar página para verificar el estado del pago
  router.reload({ only: ['pagos'] });
  cerrarModalQR();
  router.visit(route('alumno.pagos'));
};
</script>
