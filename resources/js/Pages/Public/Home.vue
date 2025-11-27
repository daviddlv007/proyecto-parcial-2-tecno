<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps<{
  cursos?: Array<{
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    duracion_horas: number;
    tipo_curso: { nombre: string };
  }>;
}>();
</script>

<template>
  <GuestLayout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-red-600 via-red-700 to-yellow-600 overflow-hidden">
      <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')]"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <div class="flex justify-center mb-8">
            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-2xl">
              <svg class="w-20 h-20 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
              </svg>
            </div>
          </div>
          
          <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
            Automóvil Club Boliviano
          </h1>
          <p class="text-2xl md:text-3xl text-yellow-100 font-semibold mb-4">
            Escuela de Conducción - Santa Cruz
          </p>
          <p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-8">
            Más de 50 años formando conductores responsables y seguros. Tu camino hacia la libertad comienza aquí.
          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link 
              :href="route('register')" 
              class="inline-flex items-center px-8 py-4 bg-white text-red-600 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
              </svg>
              Registrarse Ahora
            </Link>
            
            <Link 
              :href="route('cursos.publicos')" 
              class="inline-flex items-center px-8 py-4 bg-yellow-400 text-gray-900 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              Ver Cursos
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">¿Por qué elegir ACB?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Instructores Certificados</h3>
            <p class="text-gray-600">Profesionales con años de experiencia y certificación nacional</p>
          </div>

          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Instalaciones Modernas</h3>
            <p class="text-gray-600">Aulas equipadas y circuitos de práctica en Santa Cruz</p>
          </div>

          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Horarios Flexibles</h3>
            <p class="text-gray-600">Clases en horarios matutinos, vespertinos y fines de semana</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Cursos Destacados -->
    <div class="py-16 bg-white" v-if="cursos && cursos.length > 0">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">Nuestros Cursos</h2>
        <p class="text-center text-gray-600 mb-12">Programas diseñados para cada nivel de experiencia</p>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div 
            v-for="curso in cursos.slice(0, 3)" 
            :key="curso.id"
            class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1"
          >
            <div class="flex items-center justify-between mb-4">
              <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                {{ curso.tipo_curso.nombre }}
              </span>
              <span class="text-2xl font-bold text-gray-900">Bs. {{ curso.precio }}</span>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ curso.nombre }}</h3>
            <p class="text-gray-600 mb-4 line-clamp-2">{{ curso.descripcion }}</p>
            
            <div class="flex items-center text-sm text-gray-500 mb-4">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              {{ curso.duracion_horas }} horas
            </div>
            
            <Link 
              :href="route('cursos.publicos')" 
              class="block w-full text-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors"
            >
              Más Información
            </Link>
          </div>
        </div>
        
        <div class="text-center mt-8">
          <Link 
            :href="route('cursos.publicos')" 
            class="inline-flex items-center text-red-600 font-semibold hover:text-red-700"
          >
            Ver todos los cursos
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-red-600 to-yellow-600 py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
          ¿Listo para Comenzar tu Aventura?
        </h2>
        <p class="text-xl text-white/90 mb-8">
          Únete a los miles de conductores formados en el Automóvil Club Boliviano
        </p>
        <Link 
          :href="route('register')" 
          class="inline-flex items-center px-8 py-4 bg-white text-red-600 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
        >
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          Comenzar Ahora
        </Link>
      </div>
    </div>

    <!-- Footer Info -->
    <div class="bg-gray-900 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 text-white">
          <div>
            <h3 class="text-lg font-bold mb-4">Contacto</h3>
            <p class="text-gray-400">Santa Cruz de la Sierra, Bolivia</p>
            <p class="text-gray-400">Tel: (3) 333-3333</p>
            <p class="text-gray-400">info@acb.com.bo</p>
          </div>
          
          <div>
            <h3 class="text-lg font-bold mb-4">Horario de Atención</h3>
            <p class="text-gray-400">Lunes a Viernes: 8:00 - 18:00</p>
            <p class="text-gray-400">Sábados: 8:00 - 13:00</p>
            <p class="text-gray-400">Domingos: Cerrado</p>
          </div>
          
          <div>
            <h3 class="text-lg font-bold mb-4">Enlaces Rápidos</h3>
            <div class="space-y-2">
              <Link :href="route('cursos.publicos')" class="block text-gray-400 hover:text-white transition-colors">Cursos</Link>
              <Link :href="route('nosotros')" class="block text-gray-400 hover:text-white transition-colors">Nosotros</Link>
              <Link :href="route('login')" class="block text-gray-400 hover:text-white transition-colors">Iniciar Sesión</Link>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2024 Automóvil Club Boliviano - Escuela de Conducción Santa Cruz. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
