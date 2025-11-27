<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Mi Perfil</h1>
            <p class="page-subtitle">Información personal del usuario</p>
          </div>
        </div>

        <!-- Personal Information Card -->
        <Card class="mb-6">
          <div class="p-6">
            <h2 class="section-title mb-6">Información Personal</h2>
            
            <form @submit.prevent="updateProfile" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="profileForm.nombre"
                  label="Nombre"
                  placeholder="Nombre"
                  required
                  :error="profileForm.errors.nombre"
                />

                <Input
                  v-model="profileForm.apellido"
                  label="Apellido"
                  placeholder="Apellido"
                  required
                  :error="profileForm.errors.apellido"
                />
              </div>

              <Input
                v-model="profileForm.email"
                type="email"
                label="Correo Electrónico"
                placeholder="correo@ejemplo.com"
                required
                :error="profileForm.errors.email"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="profileForm.telefono"
                  label="Teléfono"
                  placeholder="+593 123 456 789"
                  :error="profileForm.errors.telefono"
                />

                <Input
                  v-model="profileForm.fecha_nacimiento"
                  type="date"
                  label="Fecha de Nacimiento"
                  :error="profileForm.errors.fecha_nacimiento"
                />
              </div>

              <Select
                v-model="profileForm.genero"
                label="Género"
                :options="[
                  { value: 'Masculino', label: 'Masculino' },
                  { value: 'Femenino', label: 'Femenino' },
                  { value: 'Otro', label: 'Otro' }
                ]"
                :error="profileForm.errors.genero"
              />

              <div class="form-actions">
                <Button type="submit" variant="primary" :disabled="profileForm.processing">
                  {{ profileForm.processing ? 'Guardando...' : 'Guardar Cambios' }}
                </Button>
              </div>
            </form>
          </div>
        </Card>

        <!-- Change Password Card -->
        <Card>
          <div class="p-6">
            <h2 class="section-title mb-6">Cambiar Contraseña</h2>
            
            <form @submit.prevent="updatePassword" class="space-y-4">
              <Input
                v-model="passwordForm.current_password"
                type="password"
                label="Contraseña Actual"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                :error="passwordForm.errors.current_password"
              />

              <Input
                v-model="passwordForm.password"
                type="password"
                label="Nueva Contraseña"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :error="passwordForm.errors.password"
              />

              <Input
                v-model="passwordForm.password_confirmation"
                type="password"
                label="Confirmar Nueva Contraseña"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :error="passwordForm.errors.password_confirmation"
              />

              <div class="form-actions">
                <Button type="submit" variant="primary" :disabled="passwordForm.processing">
                  {{ passwordForm.processing ? 'Actualizando...' : 'Actualizar Contraseña' }}
                </Button>
              </div>
            </form>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const profileForm = useForm({
  nombre: user.value?.nombre || '',
  apellido: user.value?.apellido || '',
  email: user.value?.email || '',
  telefono: user.value?.telefono || '',
  fecha_nacimiento: user.value?.fecha_nacimiento || '',
  genero: user.value?.genero || ''
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const updateProfile = () => {
  profileForm.put(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      // Success notification could be added here
    }
  });
};

const updatePassword = () => {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
    }
  });
};
</script>
