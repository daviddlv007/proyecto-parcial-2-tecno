<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
  <GuestLayout>
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-yellow-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Sobre Nosotros</h1>
        <p class="text-xl text-white/90">Automóvil Club Boliviano - Escuela de Conducción</p>
      </div>
    </div>

    <!-- Historia -->
    <div class="py-16 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Historia</h2>
          <div class="w-24 h-1 bg-red-600 mx-auto mb-6"></div>
        </div>
        
        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
          <p class="text-lg mb-6">
            El <strong>Automóvil Club Boliviano (ACB)</strong> en Santa Cruz de la Sierra es una institución con más de 50 años de trayectoria 
            en la formación de conductores responsables y seguros. Nos hemos consolidado como la escuela de conducción líder en el oriente boliviano, 
            comprometidos con la excelencia educativa y la seguridad vial.
          </p>
          
          <p class="mb-6">
            Nuestra misión es proporcionar educación vial de calidad, formando conductores conscientes de su responsabilidad en las calles 
            y carreteras. A través de programas integrales que combinan teoría y práctica, preparamos a nuestros alumnos para enfrentar 
            con confianza los desafíos de la conducción moderna.
          </p>
          
          <p class="mb-6">
            Contamos con instructores certificados, vehículos modernos y circuitos de práctica especialmente diseñados para garantizar 
            una experiencia de aprendizaje segura y efectiva.
          </p>
        </div>
      </div>
    </div>

    <!-- Valores -->
    <div class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Nuestros Valores</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="bg-white rounded-xl p-6 shadow-lg text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Seguridad</h3>
            <p class="text-gray-600">Priorizamos la seguridad en cada aspecto de nuestra formación</p>
          </div>

          <div class="bg-white rounded-xl p-6 shadow-lg text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Excelencia</h3>
            <p class="text-gray-600">Compromiso con la calidad educativa y profesionalismo</p>
          </div>

          <div class="bg-white rounded-xl p-6 shadow-lg text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Responsabilidad</h3>
            <p class="text-gray-600">Formamos conductores conscientes y responsables</p>
          </div>

          <div class="bg-white rounded-xl p-6 shadow-lg text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Innovación</h3>
            <p class="text-gray-600">Metodologías modernas y tecnología actualizada</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Contacto -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Información de Contacto</h2>
        
        <div class="grid md:grid-cols-2 gap-12">
          <!-- Información de Contacto -->
          <div class="space-y-6">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-bold text-gray-900">Dirección</h3>
                <p class="text-gray-600 mt-1">Santa Cruz de la Sierra, Bolivia</p>
                <p class="text-gray-600">Av. Principal (Referencia ACB)</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-bold text-gray-900">Teléfono</h3>
                <p class="text-gray-600 mt-1">(3) 333-3333</p>
                <p class="text-gray-600">WhatsApp: +591 7XXXXXXX</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-bold text-gray-900">Email</h3>
                <p class="text-gray-600 mt-1">info@acb.com.bo</p>
                <p class="text-gray-600">escuela@acb.com.bo</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-bold text-gray-900">Horario de Atención</h3>
                <p class="text-gray-600 mt-1">Lunes a Viernes: 8:00 - 18:00</p>
                <p class="text-gray-600">Sábados: 8:00 - 13:00</p>
                <p class="text-gray-600">Domingos: Cerrado</p>
              </div>
            </div>
          </div>

          <!-- Mapa o Imagen -->
          <div class="bg-gray-100 rounded-xl overflow-hidden shadow-lg">
            <div class="h-full flex items-center justify-center p-12">
              <div class="text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Visítanos</h3>
                <p class="text-gray-600">Estamos ubicados en Santa Cruz de la Sierra</p>
                <p class="text-gray-600 mb-4">Te esperamos en nuestras instalaciones</p>
                <a 
                  href="https://maps.google.com" 
                  target="_blank"
                  class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                  </svg>
                  Ver en Google Maps
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Final -->
    <div class="bg-gradient-to-r from-red-600 to-yellow-600 py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿Listo para Comenzar?</h2>
        <p class="text-xl text-white/90 mb-8">
          Únete a la familia ACB y aprende a conducir con los mejores
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link 
            :href="route('register')" 
            class="inline-flex items-center px-8 py-4 bg-white text-red-600 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
          >
            Registrarse Ahora
          </Link>
          <Link 
            :href="route('cursos.publicos')" 
            class="inline-flex items-center px-8 py-4 border-2 border-white text-white text-lg font-bold rounded-lg hover:bg-white hover:text-red-600 transition-all"
          >
            Ver Cursos
          </Link>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
