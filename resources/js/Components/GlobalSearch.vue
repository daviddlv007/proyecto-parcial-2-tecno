<template>
  <div class="relative" v-click-outside="closeResults">
    <!-- Search Input -->
    <div class="relative">
      <input
        v-model="query"
        @input="handleSearch"
        @focus="showResults = true"
        @keydown.down.prevent="highlightNext"
        @keydown.up.prevent="highlightPrevious"
        @keydown.enter.prevent="selectHighlighted"
        @keydown.esc="closeResults"
        type="text"
        placeholder="Buscar en todo el sistema..."
        class="w-64 pl-10 pr-4 py-2 border border-theme rounded-lg bg-theme-surface text-theme-primary placeholder-text-theme-tertiary focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
      <svg
        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-theme-tertiary"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
        />
      </svg>
      
      <!-- Loading Spinner -->
      <div v-if="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Results Dropdown -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="showResults && (hasResults || query.length > 0)"
        class="absolute top-full mt-2 w-[600px] max-h-[70vh] overflow-auto bg-theme-surface border border-theme rounded-lg shadow-2xl z-50"
      >
        <!-- No Query -->
        <div v-if="query.length === 0" class="p-4 text-center text-theme-tertiary text-sm">
          Escribe para buscar en TODO el sistema (15 tablas)...
        </div>

        <!-- Loading -->
        <div v-else-if="loading" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <p class="mt-2 text-theme-secondary text-sm">Buscando...</p>
        </div>

        <!-- No Results -->
        <div v-else-if="!hasResults" class="p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-theme-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="mt-2 text-theme-primary font-medium">No se encontraron resultados</p>
          <p class="text-sm text-theme-tertiary">Intenta con otros términos de búsqueda</p>
        </div>

        <!-- Results -->
        <div v-else class="py-2">
          <!-- Usuarios -->
          <div v-if="results.usuarios?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Usuarios ({{ results.usuarios.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.usuarios"
              :key="`user-${item.id}`"
              :href="`/admin/usuarios/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('usuarios', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-blue-600 font-semibold text-sm">{{ item.nombre?.[0]?.toUpperCase() }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }} {{ item.apellido }}</p>
                  <p class="text-theme-tertiary text-sm truncate">{{ item.email }} • {{ item.rol?.nombre }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Cursos -->
          <div v-if="results.cursos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Cursos ({{ results.cursos.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.cursos"
              :key="`curso-${item.id}`"
              :href="`/admin/cursos/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('cursos', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }}</p>
                  <p class="text-theme-tertiary text-sm">{{ item.tipo_curso?.nombre }} • ${{ item.precio }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Vehículos -->
          <div v-if="results.vehiculos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Vehículos ({{ results.vehiculos.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.vehiculos"
              :key="`vehiculo-${item.id}`"
              :href="`/admin/vehiculos/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('vehiculos', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.placa }} - {{ item.marca }} {{ item.modelo }}</p>
                  <p class="text-theme-tertiary text-sm">{{ item.tipo_vehiculo?.nombre }} • Año {{ item.anio }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Inscripciones -->
          <div v-if="results.inscripciones?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Inscripciones ({{ results.inscripciones.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.inscripciones"
              :key="`inscripcion-${item.id}`"
              :href="`/admin/inscripciones/${item.id}`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('inscripciones', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">
                    {{ item.usuario?.nombre }} {{ item.usuario?.apellido }} - {{ item.curso_edicion?.curso?.nombre }}
                  </p>
                  <p class="text-theme-tertiary text-sm">{{ item.estado_inscripcion }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Pagos -->
          <div v-if="results.pagos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pagos ({{ results.pagos.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.pagos"
              :key="`pago-${item.id}`"
              :href="`/admin/pagos/${item.id}`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('pagos', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-emerald-600 font-bold text-sm">$</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">
                    Pago #{{ item.id }} - ${{ item.monto }}
                  </p>
                  <p class="text-theme-tertiary text-sm">{{ item.metodo_pago?.nombre }} • {{ formatDate(item.fecha) }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Ediciones de Cursos -->
          <div v-if="results.curso_ediciones?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Ediciones de Cursos ({{ results.curso_ediciones.length }})</h3>
            </div>
            <Link v-for="item in results.curso_ediciones" :key="`edicion-${item.id}`" :href="`/admin/curso-ediciones/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover transition-colors">
              <p class="text-theme-primary font-medium">{{ item.curso?.nombre }} - Edición #{{ item.id }}</p>
              <p class="text-theme-tertiary text-sm">Cupo: {{ item.cupo_maximo }}</p>
            </Link>
          </div>

          <!-- Tipos de Curso -->
          <div v-if="results.tipos_curso?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Tipos de Curso ({{ results.tipos_curso.length }})</h3>
            </div>
            <Link v-for="item in results.tipos_curso" :key="`tipo-curso-${item.id}`" :href="`/admin/tipos-curso/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }}</p>
            </Link>
          </div>

          <!-- Tipos de Vehículo -->
          <div v-if="results.tipos_vehiculo?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Tipos de Vehículo ({{ results.tipos_vehiculo.length }})</h3>
            </div>
            <Link v-for="item in results.tipos_vehiculo" :key="`tipo-vehiculo-${item.id}`" :href="`/admin/tipos-vehiculo/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }}</p>
            </Link>
          </div>

          <!-- Métodos de Pago -->
          <div v-if="results.metodos_pago?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Métodos de Pago ({{ results.metodos_pago.length }})</h3>
            </div>
            <Link v-for="item in results.metodos_pago" :key="`metodo-${item.id}`" :href="`/admin/metodos-pago/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }}</p>
            </Link>
          </div>

          <!-- Planes de Pago -->
          <div v-if="results.planes_pago?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Planes de Pago ({{ results.planes_pago.length }})</h3>
            </div>
            <Link v-for="item in results.planes_pago" :key="`plan-${item.id}`" :href="`/admin/planes-pago/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }} - {{ item.numero_cuotas }} cuotas</p>
            </Link>
          </div>

          <!-- Roles -->
          <div v-if="results.roles?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Roles ({{ results.roles.length }})</h3>
            </div>
            <Link v-for="item in results.roles" :key="`rol-${item.id}`" :href="`/admin/roles/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }}</p>
            </Link>
          </div>

          <!-- Menús -->
          <div v-if="results.menus?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Menús ({{ results.menus.length }})</h3>
            </div>
            <Link v-for="item in results.menus" :key="`menu-${item.id}`" :href="`/admin/menus/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }} - {{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">Rol: {{ item.rol?.nombre || 'Todos' }}</p>
            </Link>
          </div>

          <!-- Horarios -->
          <div v-if="results.horarios?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Horarios ({{ results.horarios.length }})</h3>
            </div>
            <div v-for="item in results.horarios" :key="`horario-${item.id}`" class="px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.curso_edicion?.curso?.nombre }}</p>
              <p class="text-theme-tertiary text-sm">{{ item.dia_semana }} {{ item.hora_inicio }}-{{ item.hora_fin }}</p>
            </div>
          </div>

          <!-- Eventos -->
          <div v-if="results.eventos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Eventos del Sistema ({{ results.eventos.length }})</h3>
            </div>
            <Link v-for="item in results.eventos" :key="`evento-${item.id}`" :href="`/admin/eventos`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">{{ item.descripcion || 'Sin descripción' }} • {{ formatDate(item.created_at) }}</p>
            </Link>
          </div>

          <!-- Visitas -->
          <div v-if="results.visitas?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Visitas ({{ results.visitas.length }})</h3>
            </div>
            <Link v-for="item in results.visitas" :key="`visita-${item.id}`" :href="`/admin/visitas`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">{{ item.contador }} visitas</p>
            </Link>
          </div>

          <!-- Total Count -->
          <div class="px-4 py-3 bg-theme-secondary border-t border-theme text-center">
            <p class="text-xs text-theme-tertiary">
              Total: {{ totalResults }} resultado{{ totalResults !== 1 ? 's' : '' }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const query = ref('');
const results = ref({});
const loading = ref(false);
const showResults = ref(false);
const highlightedIndex = ref(-1);

let searchTimeout = null;

const hasResults = computed(() => {
  return Object.values(results.value).some(arr => arr && arr.length > 0);
});

const totalResults = computed(() => {
  return Object.values(results.value).reduce((sum, arr) => sum + (arr?.length || 0), 0);
});

const handleSearch = () => {
  clearTimeout(searchTimeout);
  
  if (query.value.length < 2) {
    results.value = {};
    return;
  }

  loading.value = true;
  highlightedIndex.value = -1;

  searchTimeout = setTimeout(async () => {
    try {
      const response = await axios.get('/api/search', {
        params: { q: query.value }
      });
      results.value = response.data;
    } catch (error) {
      console.error('Search error:', error);
      results.value = {};
    } finally {
      loading.value = false;
    }
  }, 300); // Debounce 300ms
};

const closeResults = () => {
  showResults.value = false;
  highlightedIndex.value = -1;
};

const getGlobalIndex = (category, localIndex) => {
  const categories = ['usuarios', 'cursos', 'vehiculos', 'inscripciones', 'pagos'];
  let globalIndex = 0;
  
  for (const cat of categories) {
    if (cat === category) {
      return globalIndex + localIndex;
    }
    globalIndex += results.value[cat]?.length || 0;
  }
  return globalIndex;
};

const highlightNext = () => {
  const max = totalResults.value - 1;
  if (highlightedIndex.value < max) {
    highlightedIndex.value++;
  }
};

const highlightPrevious = () => {
  if (highlightedIndex.value > 0) {
    highlightedIndex.value--;
  }
};

const selectHighlighted = () => {
  if (highlightedIndex.value < 0) return;
  
  let currentIndex = 0;
  const categories = ['usuarios', 'cursos', 'vehiculos', 'inscripciones', 'pagos'];
  
  for (const category of categories) {
    const items = results.value[category] || [];
    if (currentIndex + items.length > highlightedIndex.value) {
      const localIndex = highlightedIndex.value - currentIndex;
      const item = items[localIndex];
      let url = '';
      
      switch(category) {
        case 'usuarios':
          url = `/admin/usuarios/${item.id}/edit`;
          break;
        case 'cursos':
          url = `/admin/cursos/${item.id}/edit`;
          break;
        case 'vehiculos':
          url = `/admin/vehiculos/${item.id}/edit`;
          break;
        case 'inscripciones':
          url = `/admin/inscripciones/${item.id}`;
          break;
        case 'pagos':
          url = `/admin/pagos/${item.id}`;
          break;
      }
      
      if (url) {
        window.location.href = url;
      }
      return;
    }
    currentIndex += items.length;
  }
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  }
};
</script>
