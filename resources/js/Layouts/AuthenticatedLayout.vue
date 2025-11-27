<template>
  <div class="min-h-screen flex bg-theme-primary">
    <!-- Sidebar -->
    <aside
      :class="[
        'sidebar-bg border-r sidebar-border transition-all duration-300 flex flex-col',
        sidebarCollapsed ? 'w-16' : 'w-64'
      ]"
    >
      <!-- Logo / Brand -->
      <div class="p-4 border-b sidebar-border flex items-center justify-between">
        <Link href="/admin/dashboard" class="flex items-center gap-3">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <span v-if="!sidebarCollapsed" class="font-bold text-lg sidebar-text">Escuela</span>
        </Link>
        <button
          @click="sidebarCollapsed = !sidebarCollapsed"
          class="p-1 rounded hover:bg-theme-hover sidebar-text"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sidebarCollapsed ? 'M13 5l7 7-7 7M5 5l7 7-7 7' : 'M11 19l-7-7 7-7m8 14l-7-7 7-7'" />
          </svg>
        </button>
      </div>

      <!-- Menu Items -->
      <nav class="flex-1 overflow-y-auto py-4">
        <Link
          v-for="item in dynamicMenuItems"
          :key="item.id"
          :href="item.href"
          :class="[
            'flex items-center gap-3 px-4 py-3 sidebar-text sidebar-item-hover transition-colors',
            $page.url.startsWith(item.href) ? 'sidebar-item-active font-medium' : ''
          ]"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
          </svg>
          <span v-if="!sidebarCollapsed">{{ item.name }}</span>
        </Link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar -->
      <header class="topbar-bg border-b topbar-border sticky top-0 z-10">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <button
              @click="sidebarCollapsed = !sidebarCollapsed"
              class="lg:hidden p-2 rounded-lg hover:bg-theme-hover"
            >
              <svg class="w-6 h-6 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
            
            <h1 class="text-xl font-semibold text-theme-primary">
              {{ pageTitle }}
            </h1>
          </div>

          <div class="flex items-center gap-3">
            <!-- Global Search (Admin only) -->
            <GlobalSearch v-if="isAdmin" />
            
            <!-- Appearance Controls (theme base, font, contrast, mode) -->
            <AppearanceControls />

            <!-- User Dropdown -->
            <div class="relative">
              <button
                @click="userDropdownOpen = !userDropdownOpen"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-theme-hover"
              >
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                  <span class="text-white text-sm font-medium">
                    {{ user?.nombre?.charAt(0) || 'U' }}
                  </span>
                </div>
                <span v-if="!sidebarCollapsed" class="text-sm font-medium text-theme-primary hidden sm:block">
                  {{ user?.nombre || 'Usuario' }}
                </span>
                <svg class="w-4 h-4 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div
                v-show="userDropdownOpen"
                @click.away="userDropdownOpen = false"
                class="absolute right-0 mt-2 w-48 bg-theme-surface border border-theme rounded-lg shadow-lg py-1 z-50"
              >
                <div class="px-4 py-2 border-b border-theme">
                  <p class="text-sm font-medium text-theme-primary">{{ user?.nombre }}</p>
                  <p class="text-xs text-theme-secondary">{{ user?.email }}</p>
                </div>
                <Link
                  :href="route('profile.edit')"
                  class="block px-4 py-2 text-sm text-theme-primary hover:bg-theme-secondary transition-colors"
                >
                  Mi Perfil
                </Link>
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-theme-secondary transition-colors"
                >
                  Cerrar Sesión
                </Link>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <slot />
      </main>

      <!-- Footer -->
      <footer class="footer-bg border-t footer-border py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
          <div class="text-sm text-theme-secondary">
            © {{ new Date().getFullYear() }} Escuela de Conducción. Todos los derechos reservados.
          </div>
          <div class="flex items-center gap-2 text-sm text-theme-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span>{{ visitasPagina }} {{ visitasPagina === 1 ? 'visita' : 'visitas' }}</span>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppearanceControls from '@/Components/AppearanceControls.vue';
import GlobalSearch from '@/Components/GlobalSearch.vue';

interface User {
  id: number;
  nombre: string;
  apellido: string;
  email: string;
  rol_id: number;
}

interface PageProps {
  auth: {
    user: User;
  };
  visitasPagina: number;
}

const sidebarCollapsed = ref(false);
const userDropdownOpen = ref(false);
const page = usePage<PageProps>();

const user = computed(() => page.props.auth?.user);
const visitasPagina = computed(() => page.props.visitasPagina || 0);
const userRole = computed(() => user.value?.rol_id);
const isAdmin = computed(() => userRole.value === 1);

const pageTitle = computed(() => {
  const url = page.url;
  if (url.includes('/dashboard')) return 'Dashboard';
  if (url.includes('/usuarios')) return 'Usuarios';
  if (url.includes('/curso-ediciones')) return 'Ediciones de Cursos';
  if (url.includes('/cursos')) return 'Cursos';
  if (url.includes('/alumnos')) return 'Alumnos';
  if (url.includes('/horarios')) return 'Horarios';
  if (url.includes('/vehiculos')) return 'Vehículos';
  if (url.includes('/inscripciones')) return 'Inscripciones';
  if (url.includes('/pagos')) return 'Pagos';
  if (url.includes('/database')) return 'Administrador BD';
  return 'Sistema';
});

// Menú dinámico desde la base de datos
const dynamicMenuItems = computed(() => page.props.menu || []);
</script>
