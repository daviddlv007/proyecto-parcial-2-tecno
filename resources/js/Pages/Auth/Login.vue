<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import { useValidation } from '@/composables/useValidation';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const { errors: validationErrors, validate, clearErrors } = useValidation();

const submit = () => {
  clearErrors();
  
  // Validación frontend
  const isValid = validate(form, {
    email: { 
      required: true, 
      email: true,
      message: 'Debe ingresar un correo electrónico válido'
    },
    password: { 
      required: true, 
      minLength: 6,
      message: 'La contraseña debe tener al menos 6 caracteres'
    }
  });

  if (!isValid) {
    return;
  }

  console.log('🔵 LOGIN ATTEMPT:', { email: form.email });
  
  form.post(route('login'), {
    onSuccess: (page) => {
      console.log('✅ LOGIN SUCCESS - Redirect URL:', window.location.href);
      console.log('✅ Page props:', page.props);
    },
    onError: (errors) => {
      console.log('❌ LOGIN ERROR:', errors);
    },
    onFinish: () => {
      console.log('🔄 LOGIN FINISHED');
      form.reset('password');
    },
  });
};

const quickLogin = (email: string) => {
  form.email = email;
  form.password = 'password';
  form.remember = true;
};
</script>

<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full">
        <div class="card p-8">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-theme-primary">Iniciar Sesión</h2>
            <p class="mt-2 text-sm text-theme-secondary">Accede a tu panel de control</p>
          </div>

          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label for="email" class="block text-sm font-medium text-theme-primary mb-2">Correo Electrónico</label>
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                autocomplete="email"
                class="input" 
                :class="{ 'border-red-500': validationErrors.email || form.errors.email }"
                placeholder="tu@email.com"
              />
              <p v-if="validationErrors.email" class="mt-1 text-sm text-red-600">{{ validationErrors.email }}</p>
              <p v-else-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-theme-primary mb-2">Contraseña</label>
              <input 
                id="password" 
                v-model="form.password" 
                type="password" 
                autocomplete="current-password"
                class="input" 
                :class="{ 'border-red-500': validationErrors.password || form.errors.password }"
                placeholder="••••••••"
              />
              <p v-if="validationErrors.password" class="mt-1 text-sm text-red-600">{{ validationErrors.password }}</p>
              <p v-else-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
            </div>

            <div class="flex items-center">
              <input id="remember" v-model="form.remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"/>
              <label for="remember" class="ml-2 block text-sm text-theme-secondary">Recordarme</label>
            </div>

            <Button type="submit" :disabled="form.processing" variant="primary" class="w-full">
              <span v-if="!form.processing">Iniciar Sesión</span>
              <span v-else class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Ingresando...
              </span>
            </Button>
          </form>

          <div class="mt-8 border-t border-theme pt-6">
            <p class="text-xs text-theme-secondary text-center mb-4">Usuarios de prueba:</p>
            <div class="grid grid-cols-3 gap-2 text-xs">
              <button @click="quickLogin('admin@escuela.com')" type="button" class="px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors font-medium">Admin</button>
              <button @click="quickLogin('profesor@escuela.com')" type="button" class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors font-medium">Profesor</button>
              <button @click="quickLogin('alumno@escuela.com')" type="button" class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors font-medium">Alumno</button>
            </div>
            <p class="text-xs text-theme-tertiary text-center mt-2">Contraseña: password</p>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
