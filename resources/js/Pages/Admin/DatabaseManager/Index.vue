<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Administración de Base de Datos</h1>
            <p class="page-subtitle">Herramientas de mantenimiento y gestión de datos</p>
          </div>
        </div>

        <!-- Estadísticas -->
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Estadísticas Actuales</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div v-for="(count, table) in stats" :key="table" class="bg-theme-secondary p-4 rounded-lg">
              <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count }}</div>
              <div class="text-sm text-theme-secondary capitalize">{{ table.replace('_', ' ') }}</div>
            </div>
          </div>
        </Card>

        <!-- Usuarios de Prueba -->
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">👤 Usuarios de Prueba</h3>
          <p class="text-sm text-theme-secondary mb-4">
            Estos usuarios se crean automáticamente al poblar la BD. Haz clic para usar sus credenciales en login.
          </p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4 rounded">
              <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"/>
                </svg>
                <h4 class="font-semibold text-red-800 dark:text-red-300">Administrador</h4>
              </div>
              <div class="text-xs text-red-700 dark:text-red-400 mb-3 space-y-1">
                <div><strong>Email:</strong> {{ testUsers.admin.email }}</div>
                <div><strong>Pass:</strong> {{ testUsers.admin.password }}</div>
                <div class="mt-2">
                  <span v-if="testUsers.admin.exists" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    ✓ Existe
                  </span>
                  <span v-else class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    ✗ No creado
                  </span>
                </div>
              </div>
              <a :href="route('login')" target="_blank" class="block">
                <Button variant="secondary" size="sm" class="w-full bg-red-600 hover:bg-red-700 text-white">
                  Ir a Login
                </Button>
              </a>
            </div>

            <div class="border-l-4 border-purple-500 bg-purple-50 dark:bg-purple-900/20 p-4 rounded">
              <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
                <h4 class="font-semibold text-purple-800 dark:text-purple-300">Profesor</h4>
              </div>
              <div class="text-xs text-purple-700 dark:text-purple-400 mb-3 space-y-1">
                <div><strong>Email:</strong> {{ testUsers.profesor.email }}</div>
                <div><strong>Pass:</strong> {{ testUsers.profesor.password }}</div>
                <div class="mt-2">
                  <span v-if="testUsers.profesor.exists" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    ✓ Existe
                  </span>
                  <span v-else class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    ✗ No creado
                  </span>
                </div>
              </div>
              <a :href="route('login')" target="_blank" class="block">
                <Button variant="secondary" size="sm" class="w-full bg-purple-600 hover:bg-purple-700 text-white">
                  Ir a Login
                </Button>
              </a>
            </div>

            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4 rounded">
              <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                <h4 class="font-semibold text-blue-800 dark:text-blue-300">Alumno</h4>
              </div>
              <div class="text-xs text-blue-700 dark:text-blue-400 mb-3 space-y-1">
                <div><strong>Email:</strong> {{ testUsers.alumno.email }}</div>
                <div><strong>Pass:</strong> {{ testUsers.alumno.password }}</div>
                <div class="mt-2">
                  <span v-if="testUsers.alumno.exists" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    ✓ Existe
                  </span>
                  <span v-else class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    ✗ No creado
                  </span>
                </div>
              </div>
              <a :href="route('login')" target="_blank" class="block">
                <Button variant="secondary" size="sm" class="w-full bg-blue-600 hover:bg-blue-700 text-white">
                  Ir a Login
                </Button>
              </a>
            </div>
          </div>
        </Card>

        <!-- Acciones -->
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Acciones de Mantenimiento</h3>
          <div class="space-y-4">
            
            <!-- Limpiar BD -->
            <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded">
              <div class="flex items-start justify-between">
                <div>
                  <h4 class="font-semibold text-yellow-800 dark:text-yellow-300">Limpiar Base de Datos</h4>
                  <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">Elimina TODOS los datos excepto roles, tipos de vehículo/curso, métodos y planes de pago.</p>
                </div>
                <Button 
                  @click="confirmTruncate" 
                  variant="secondary"
                  class="ml-4 bg-yellow-600 hover:bg-yellow-700 text-white whitespace-nowrap"
                >
                  Limpiar BD
                </Button>
              </div>
            </div>

            <!-- Poblar BD -->
            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4 rounded">
              <div class="flex items-start justify-between">
                <div>
                  <h4 class="font-semibold text-blue-800 dark:text-blue-300">Poblar con Datos de Demo</h4>
                  <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">Inserta 100+ registros de ejemplo: usuarios, vehículos, cursos, inscripciones, pagos.</p>
                </div>
                <Button 
                  @click="confirmSeed" 
                  variant="primary"
                  class="ml-4 whitespace-nowrap"
                >
                  Poblar BD
                </Button>
              </div>
            </div>

            <!-- Resetear (Limpiar + Poblar) -->
            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4 rounded">
              <div class="flex items-start justify-between">
                <div>
                  <h4 class="font-semibold text-red-800 dark:text-red-300">Resetear Completamente</h4>
                  <p class="text-sm text-red-700 dark:text-red-400 mt-1">Limpia la BD completamente y la puebla con datos de demostración (100+ registros).</p>
                </div>
                <Button 
                  @click="confirmReset" 
                  variant="danger"
                  class="ml-4 whitespace-nowrap"
                >
                  Resetear BD
                </Button>
              </div>
            </div>

          </div>
        </Card>

        <!-- Advertencia -->
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
            </svg>
            <div class="text-sm text-orange-800 dark:text-orange-300">
              <strong>Advertencia:</strong> Estas operaciones son irreversibles. Asegúrese de hacer respaldo antes de limpiar datos.
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal de Confirmación -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showModal = false">
      <Card class="max-w-md">
        <h3 class="text-lg font-bold text-theme-primary mb-4">{{ modalTitle }}</h3>
        <p class="text-theme-secondary mb-6">{{ modalMessage }}</p>
        <div class="flex justify-end gap-3">
          <Button @click="showModal = false" variant="secondary">Cancelar</Button>
          <Button @click="executeAction" :disabled="processing" variant="danger">
            {{ processing ? 'Procesando...' : 'Confirmar' }}
          </Button>
        </div>
      </Card>
    </div>

  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';

defineProps<{ 
  stats: Record<string, number>;
  testUsers: {
    admin: { email: string; password: string; exists: boolean };
    profesor: { email: string; password: string; exists: boolean };
    alumno: { email: string; password: string; exists: boolean };
  };
}>();

const showModal = ref(false);
const modalTitle = ref('');
const modalMessage = ref('');
const actionType = ref('');
const processing = ref(false);

const confirmTruncate = () => {
  modalTitle.value = '¿Limpiar Base de Datos?';
  modalMessage.value = 'Esta acción eliminará TODOS los datos de usuarios, vehículos, cursos, inscripciones y pagos. Los catálogos base (roles, tipos) se mantendrán.';
  actionType.value = 'truncate';
  showModal.value = true;
};

const confirmSeed = () => {
  modalTitle.value = '¿Poblar con Datos de Demo?';
  modalMessage.value = 'Se insertarán más de 100 registros de ejemplo en la base de datos.';
  actionType.value = 'seed';
  showModal.value = true;
};

const confirmReset = () => {
  modalTitle.value = '¿Resetear Base de Datos?';
  modalMessage.value = 'Se eliminarán TODOS los datos y se poblarán nuevamente con datos de demostración (100+ registros).';
  actionType.value = 'reset';
  showModal.value = true;
};

const executeAction = () => {
  processing.value = true;
  router.post(route(`admin.database.${actionType.value}`), {}, {
    onFinish: () => {
      processing.value = false;
      showModal.value = false;
      router.reload();
    }
  });
};
</script>
