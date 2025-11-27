<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nuevo Usuario</h1>
            <p class="page-subtitle">Complete el formulario para registrar un nuevo usuario</p>
          </div>
        </div>

        <!-- Form Card -->
        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.nombre"
                  label="Nombre"
                  placeholder="Juan"
                  :error="validationErrors.nombre || form.errors.nombre"
                />

                <Input
                  v-model="form.apellido"
                  label="Apellido"
                  placeholder="Pérez"
                  :error="validationErrors.apellido || form.errors.apellido"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input
                  v-model="form.fecha_nacimiento"
                  type="date"
                  label="Fecha de Nacimiento"
                  :error="validationErrors.fecha_nacimiento || form.errors.fecha_nacimiento"
                />

                <Select
                  v-model="form.genero"
                  label="Género"
                  :options="[
                    { value: 'Masculino', label: 'Masculino' },
                    { value: 'Femenino', label: 'Femenino' },
                    { value: 'Otro', label: 'Otro' }
                  ]"
                  :error="validationErrors.genero || form.errors.genero"
                />

                <Select
                  v-model="form.tipo_documento"
                  label="Tipo Documento"
                  :options="[
                    { value: 'CI', label: 'CI' },
                    { value: 'Pasaporte', label: 'Pasaporte' },
                    { value: 'Otro', label: 'Otro' }
                  ]"
                  :error="validationErrors.tipo_documento || form.errors.tipo_documento"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.numero_documento"
                  label="Número de Documento"
                  placeholder="12345678"
                  :error="validationErrors.numero_documento || form.errors.numero_documento"
                />

                <Input
                  v-model="form.telefono"
                  label="Teléfono"
                  placeholder="099123456"
                  :error="validationErrors.telefono || form.errors.telefono"
                />
              </div>

              <Input
                v-model="form.email"
                type="email"
                label="Correo Electrónico"
                placeholder="juan@example.com"
                :error="validationErrors.email || form.errors.email"
              />

              <Input
                v-model="form.direccion"
                label="Dirección"
                placeholder="Calle Principal 123"
                :error="validationErrors.direccion || form.errors.direccion"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.contrasena"
                  type="password"
                  autocomplete="new-password"
                  label="Contraseña"
                  placeholder="••••••••"
                  :error="validationErrors.contrasena || form.errors.contrasena"
                />

                <Select
                  v-model="form.rol_id"
                  label="Rol"
                  :options="roles.map(r => ({ value: r.id, label: r.nombre }))"
                  :error="validationErrors.rol_id || form.errors.rol_id"
                />
              </div>

              <Select
                v-model="form.estado_usuario"
                label="Estado del Usuario"
                :options="[
                  { value: 'activo', label: 'Activo' },
                  { value: 'inactivo', label: 'Inactivo' },
                  { value: 'suspendido', label: 'Suspendido' }
                ]"
                :error="validationErrors.estado_usuario || form.errors.estado_usuario"
              />
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <Link :href="route('admin.usuarios.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Usuario' }}
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
import { useValidation } from '@/composables/useValidation';

const props = defineProps<{
  roles: any[];
}>();

const form = useForm({
  nombre: '',
  apellido: '',
  fecha_nacimiento: '',
  genero: '',
  tipo_documento: '',
  numero_documento: '',
  email: '',
  telefono: '',
  direccion: '',
  contrasena: '',
  rol_id: '',
  estado_usuario: 'activo',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();
  
  const isValid = validate(form, {
    nombre: { required: true, maxLength: 100 },
    apellido: { required: true, maxLength: 100 },
    fecha_nacimiento: { required: true, date: true },
    genero: { required: true },
    tipo_documento: { required: true },
    numero_documento: { required: true },
    email: { required: true, email: true },
    telefono: { required: true, maxLength: 20 },
    direccion: { required: true, maxLength: 255 },
    contrasena: { required: true, minLength: 6 },
    rol_id: { required: true },
    estado_usuario: { required: true },
  });

  if (!isValid) {
    return;
  }

  form.post(route('admin.usuarios.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>
