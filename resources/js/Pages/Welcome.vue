<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 flex items-center justify-center px-4">
    <div class="max-w-4xl w-full">
      <div class="text-center">
        <div class="mb-8">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-2xl mb-6">
            <svg class="w-14 h-14 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h1 class="text-6xl font-bold text-white mb-4">
            Escuela de Conducción
          </h1>
          <p class="text-xl text-white/90 mb-8">
            Sistema de Gestión Integral
          </p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl border border-white/20">
          <h2 class="text-2xl font-semibold text-white mb-6">
            Bienvenido al Sistema
          </h2>
          
          <div class="space-y-4">
            <Link 
              :href="route('login')" 
              class="block w-full py-4 px-6 bg-white text-blue-600 rounded-xl font-semibold text-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
            >
              Iniciar Sesión
            </Link>
            
            <div class="text-white/80 text-sm">
              <p>Sistema de gestión para escuela de conducción</p>
              <p class="mt-2">Acceso administrativo, instructores y alumnos</p>
            </div>
          </div>
        </div>

        <div class="mt-8 text-white/60 text-sm">
          <p>&copy; 2024 Escuela de Conducción. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </div>
</template>
