<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useValidation } from '@/composables/useValidation';

const form = useForm({
  nombre: '',
  apellido: '',
  email: '',
  password: '',
  password_confirmation: '',
  telefono: '',
  numero_documento: '',
  fecha_nacimiento: '',
  genero: 'Masculino',
  tipo_documento: 'CI',
});

const { errors: validationErrors, validate, clearErrors } = useValidation();

const validationRules = {
  nombre: {
    required: true,
    maxLength: 100,
    message: 'El nombre es obligatorio y no debe exceder 100 caracteres'
  },
  apellido: {
    required: true,
    maxLength: 100,
    message: 'El apellido es obligatorio y no debe exceder 100 caracteres'
  },
  email: {
    required: true,
    email: true,
    message: 'Debe ingresar un correo electrónico válido'
  },
  numero_documento: {
    required: true,
    message: 'El número de documento es obligatorio'
  },
  fecha_nacimiento: {
    required: true,
    date: true,
    message: 'La fecha de nacimiento es obligatoria'
  },
  password: {
    required: true,
    minLength: 6,
    message: 'La contraseña debe tener al menos 6 caracteres'
  },
  password_confirmation: {
    required: true,
    custom: (value: string) => value === form.password,
    message: 'Las contraseñas no coinciden'
  }
};

const submit = () => {
  clearErrors();
  if (validate(form, validationRules)) {
    form.post(route('register'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  }
};
</script>

<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
          <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-red-600 to-yellow-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Automóvil Club Boliviano</h2>
            <p class="mt-2 text-lg font-semibold text-red-600">Escuela de Conducción - Santa Cruz</p>
            <p class="mt-1 text-sm text-gray-600">Regístrate como alumno y comienza tu camino</p>
          </div>

          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                <input 
                  id="nombre" 
                  v-model="form.nombre" 
                  type="text" 
                  autocomplete="given-name"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.nombre || form.errors.nombre ? 'border-red-500' : 'border-gray-300'"
                  placeholder="Juan"
                />
                <p v-if="validationErrors.nombre" class="mt-1 text-sm text-red-600">{{ validationErrors.nombre }}</p>
                <p v-else-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</p>
              </div>

              <div>
                <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                <input 
                  id="apellido" 
                  v-model="form.apellido" 
                  type="text" 
                  autocomplete="family-name"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.apellido || form.errors.apellido ? 'border-red-500' : 'border-gray-300'"
                  placeholder="Pérez"
                />
                <p v-if="validationErrors.apellido" class="mt-1 text-sm text-red-600">{{ validationErrors.apellido }}</p>
                <p v-else-if="form.errors.apellido" class="mt-1 text-sm text-red-600">{{ form.errors.apellido }}</p>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                <input 
                  id="email" 
                  v-model="form.email" 
                  type="email" 
                  autocomplete="email"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.email || form.errors.email ? 'border-red-500' : 'border-gray-300'"
                  placeholder="tu@email.com"
                />
                <p v-if="validationErrors.email" class="mt-1 text-sm text-red-600">{{ validationErrors.email }}</p>
                <p v-else-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
              </div>

              <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                <input 
                  id="telefono" 
                  v-model="form.telefono" 
                  type="tel" 
                  autocomplete="tel"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  placeholder="7XXXXXXX"
                />
                <p v-if="form.errors.telefono" class="mt-1 text-sm text-red-600">{{ form.errors.telefono }}</p>
              </div>

              <div>
                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                <select 
                  id="tipo_documento" 
                  v-model="form.tipo_documento"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                >
                  <option value="CI">Cédula de Identidad</option>
                  <option value="Pasaporte">Pasaporte</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>

              <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">Número de Documento *</label>
                <input 
                  id="numero_documento" 
                  v-model="form.numero_documento" 
                  type="text"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.numero_documento || form.errors.numero_documento ? 'border-red-500' : 'border-gray-300'"
                  placeholder="12345678"
                />
                <p v-if="validationErrors.numero_documento" class="mt-1 text-sm text-red-600">{{ validationErrors.numero_documento }}</p>
                <p v-else-if="form.errors.numero_documento" class="mt-1 text-sm text-red-600">{{ form.errors.numero_documento }}</p>
              </div>

              <div>
                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento *</label>
                <input 
                  id="fecha_nacimiento" 
                  v-model="form.fecha_nacimiento" 
                  type="date"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.fecha_nacimiento || form.errors.fecha_nacimiento ? 'border-red-500' : 'border-gray-300'"
                />
                <p v-if="validationErrors.fecha_nacimiento" class="mt-1 text-sm text-red-600">{{ validationErrors.fecha_nacimiento }}</p>
                <p v-else-if="form.errors.fecha_nacimiento" class="mt-1 text-sm text-red-600">{{ form.errors.fecha_nacimiento }}</p>
              </div>

              <div>
                <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">Género *</label>
                <select 
                  id="genero" 
                  v-model="form.genero"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                >
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña *</label>
                <input 
                  id="password" 
                  v-model="form.password" 
                  type="password" 
                  autocomplete="new-password"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.password || form.errors.password ? 'border-red-500' : 'border-gray-300'"
                  placeholder="••••••••"
                />
                <p v-if="validationErrors.password" class="mt-1 text-sm text-red-600">{{ validationErrors.password }}</p>
                <p v-else-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                <input 
                  id="password_confirmation" 
                  v-model="form.password_confirmation" 
                  type="password" 
                  autocomplete="new-password"
                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" 
                  :class="validationErrors.password_confirmation || form.errors.password_confirmation ? 'border-red-500' : 'border-gray-300'"
                  placeholder="••••••••"
                />
                <p v-if="validationErrors.password_confirmation" class="mt-1 text-sm text-red-600">{{ validationErrors.password_confirmation }}</p>
                <p v-else-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
              </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
              <p class="text-sm text-red-800">
                <strong>Importante:</strong> Al registrarte serás creado como alumno en el sistema del Automóvil Club Boliviano. Podrás inscribirte en nuestros cursos de conducción una vez verificada tu cuenta.
              </p>
            </div>

            <button 
              type="submit" 
              :disabled="form.processing" 
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-red-600 to-yellow-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!form.processing">Crear Cuenta</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creando cuenta...
              </span>
            </button>

            <div class="text-center">
              <Link :href="route('login')" class="text-sm text-red-600 hover:text-red-800 transition-colors">
                ¿Ya tienes cuenta? Inicia sesión
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
