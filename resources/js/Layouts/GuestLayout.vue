<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const mobileMenuOpen = ref(false);
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <Link :href="route('home')" class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-yellow-500 rounded-lg flex items-center justify-center shadow-md">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
              </svg>
            </div>
            <div class="hidden sm:block">
              <span class="text-lg font-bold text-gray-900">ACB</span>
              <p class="text-xs text-gray-600">Escuela de Conducción</p>
            </div>
          </Link>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-6">
            <Link 
              :href="route('home')" 
              class="text-gray-700 hover:text-red-600 font-medium transition-colors"
            >
              Inicio
            </Link>
            <Link 
              :href="route('cursos.publicos')" 
              class="text-gray-700 hover:text-red-600 font-medium transition-colors"
            >
              Cursos
            </Link>
            <Link 
              :href="route('nosotros')" 
              class="text-gray-700 hover:text-red-600 font-medium transition-colors"
            >
              Nosotros
            </Link>
            <div class="h-6 w-px bg-gray-300"></div>
            <Link 
              :href="route('login')" 
              class="text-gray-700 hover:text-red-600 font-medium transition-colors"
            >
              Iniciar Sesión
            </Link>
            <Link 
              :href="route('register')" 
              class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white font-bold rounded-lg hover:shadow-lg transition-all"
            >
              Registrarse
            </Link>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div v-show="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200">
          <div class="space-y-2">
            <Link 
              :href="route('home')" 
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
              @click="mobileMenuOpen = false"
            >
              Inicio
            </Link>
            <Link 
              :href="route('cursos.publicos')" 
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
              @click="mobileMenuOpen = false"
            >
              Cursos
            </Link>
            <Link 
              :href="route('nosotros')" 
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
              @click="mobileMenuOpen = false"
            >
              Nosotros
            </Link>
            <div class="border-t border-gray-200 my-2"></div>
            <Link 
              :href="route('login')" 
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
              @click="mobileMenuOpen = false"
            >
              Iniciar Sesión
            </Link>
            <Link 
              :href="route('register')" 
              class="block px-4 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white font-bold rounded-lg text-center"
              @click="mobileMenuOpen = false"
            >
              Registrarse
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot></slot>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <div class="flex items-center space-x-2 mb-4">
              <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-yellow-500 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
              </div>
              <span class="text-lg font-bold text-white">ACB</span>
            </div>
            <p class="text-gray-400 text-sm">
              Automóvil Club Boliviano - Formando conductores responsables desde hace más de 50 años
            </p>
          </div>
          
          <div>
            <h3 class="text-lg font-bold text-white mb-4">Navegación</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li>
                <Link :href="route('home')" class="hover:text-white transition-colors">Inicio</Link>
              </li>
              <li>
                <Link :href="route('cursos.publicos')" class="hover:text-white transition-colors">Cursos</Link>
              </li>
              <li>
                <Link :href="route('nosotros')" class="hover:text-white transition-colors">Nosotros</Link>
              </li>
              <li>
                <Link :href="route('register')" class="hover:text-white transition-colors">Registrarse</Link>
              </li>
            </ul>
          </div>
          
          <div>
            <h3 class="text-lg font-bold text-white mb-4">Contacto</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                (3) 333-3333
              </li>
              <li class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                info@acb.com.bo
              </li>
              <li class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Santa Cruz - Bolivia
              </li>
            </ul>
          </div>
          
          <div>
            <h3 class="text-lg font-bold text-white mb-4">Horario</h3>
            <ul class="space-y-2 text-sm text-gray-400">
              <li>Lunes - Viernes</li>
              <li class="text-white font-semibold">8:00 - 18:00</li>
              <li class="mt-2">Sábados</li>
              <li class="text-white font-semibold">8:00 - 13:00</li>
            </ul>
          </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
          © 2024 Automóvil Club Boliviano - Escuela de Conducción Santa Cruz. Todos los derechos reservados.
        </div>
      </div>
    </footer>
  </div>
</template>
