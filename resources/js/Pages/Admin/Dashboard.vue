<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Line, Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend);

const props = defineProps<{
  stats: Record<string, any>;
  inscripciones_por_estado: Array<any>;
  ingresos_mensuales: Array<any>;
  cursos_populares: Array<any>;
  eventos_recientes: Array<any>;
  inscripciones_recientes: Array<any>;
}>();

const isDarkMode = ref(document.documentElement.classList.contains('dark'));

const updateTheme = () => {
  isDarkMode.value = document.documentElement.classList.contains('dark');
};

onMounted(() => {
  const observer = new MutationObserver(updateTheme);
  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
  });
  
  onUnmounted(() => observer.disconnect());
});

const chartOptions = computed(() => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { 
      legend: { 
        display: true,
        labels: {
          color: isDarkMode.value ? '#e5e7eb' : '#1f2937'
        }
      } 
    },
    scales: {
      y: {
        ticks: { color: isDarkMode.value ? '#e5e7eb' : '#1f2937' },
        grid: { color: isDarkMode.value ? '#374151' : '#e5e7eb' }
      },
      x: {
        ticks: { color: isDarkMode.value ? '#e5e7eb' : '#1f2937' },
        grid: { color: isDarkMode.value ? '#374151' : '#e5e7eb' }
      }
    }
  };
});

const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

const inscripcionesChartData = computed(() => {
  const data = props.inscripciones_por_estado.reduce((acc, item) => {
    acc.labels.push(item.estado_inscripcion);
    acc.data.push(item.total);
    return acc;
  }, { labels: [], data: [] });
  
  return {
    labels: data.labels,
    datasets: [{
      label: 'Inscripciones por Estado',
      data: data.data,
      backgroundColor: [
        'rgba(234, 179, 8, 0.8)',  // pendiente - amarillo
        'rgba(34, 197, 94, 0.8)',  // activa - verde
        'rgba(59, 130, 246, 0.8)', // completada - azul
        'rgba(239, 68, 68, 0.8)',  // cancelada - rojo
      ],
    }],
  };
});

const ingresosChartData = computed(() => {
  const labels = props.ingresos_mensuales.map(item => `${mesesNombres[item.mes - 1]} ${item.anio}`);
  const data = props.ingresos_mensuales.map(item => parseFloat(item.total));
  
  return {
    labels,
    datasets: [{
      label: 'Ingresos (Bs.)',
      data,
      borderColor: 'rgb(34, 197, 94)',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      tension: 0.4,
    }],
  };
});

const activityIcon = (tipo: string) => {
  const icons: Record<string, any> = {
    'inscripcion': { bg: 'bg-blue-500', path: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
    'pago': { bg: 'bg-green-500', path: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    'sesion': { bg: 'bg-purple-500', path: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
  };
  return icons[tipo] || icons['inscripcion'];
};

const activityBadgeColor = (tipo: string) => {
  const colors: Record<string, string> = {
    'inscripcion': 'bg-blue-100 text-blue-800',
    'pago': 'bg-green-100 text-green-800',
    'sesion': 'bg-purple-100 text-purple-800',
  };
  return colors[tipo] || 'bg-gray-100 text-gray-800';
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleString('es-ES', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>Dashboard del Administrador</template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard title="Total Cursos" :value="stats.total_cursos" change="+2" trend="up" color="blue">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </template>
        </StatCard>

        <StatCard title="Cursos Activos" :value="stats.cursos_activos" change="+3" trend="up" color="green">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </template>
        </StatCard>

        <StatCard title="Total Alumnos" :value="stats.total_alumnos" change="+12" trend="up" color="purple">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </template>
        </StatCard>

        <StatCard title="Inscripciones Pendientes" :value="stats.inscripciones_pendientes" change="-2" trend="down" color="orange">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </template>
        </StatCard>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Inscripciones por Estado</h3>
          <div class="h-64"><Bar :data="inscripcionesChartData" :options="chartOptions" /></div>
        </div>

        <div class="card p-6">
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Ingresos Mensuales</h3>
          <div class="h-64"><Line :data="ingresosChartData" :options="chartOptions" /></div>
        </div>
      </div>

      <!-- Cursos más Populares -->
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-theme">
          <h3 class="text-lg font-semibold text-theme-primary">Cursos Más Populares</h3>
        </div>
        <div class="p-6">
          <div v-if="cursos_populares.length > 0" class="space-y-4">
            <div v-for="curso in cursos_populares" :key="curso.id" class="flex items-center justify-between p-4 bg-theme-secondary rounded-lg">
              <div class="flex-1">
                <h4 class="font-medium text-theme-primary">{{ curso.nombre }}</h4>
                <p class="text-sm text-theme-secondary">Precio: Bs. {{ curso.precio }}</p>
              </div>
              <div class="flex items-center gap-2">
                <span class="badge badge-info">
                  {{ curso.total_inscripciones }} inscripciones
                </span>
              </div>
            </div>
          </div>
          <p v-else class="text-center text-theme-tertiary py-4">No hay datos disponibles</p>
        </div>
      </div>

      <!-- Actividad Reciente -->
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-theme">
          <h3 class="text-lg font-semibold text-theme-primary">Actividad Reciente</h3>
        </div>
        <div class="divide-y divide-[rgb(var(--border-primary))]">
          <div v-for="evento in eventos_recientes" :key="evento.id" class="px-6 py-4 hover:bg-[rgb(var(--interactive-hover))] transition-colors">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div :class="activityIcon(evento.tipo_evento).bg" class="p-2 rounded-full">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="activityIcon(evento.tipo_evento).path"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-theme-primary">{{ evento.descripcion }}</p>
                  <p class="text-xs text-theme-tertiary">{{ evento.usuario?.nombre || 'Sistema' }} - {{ formatDate(evento.created_at) }}</p>
                </div>
              </div>
              <span :class="activityBadgeColor(evento.tipo_evento)" class="px-3 py-1 rounded-full text-xs font-medium">{{ evento.tipo_evento }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Link :href="route('admin.cursos.index')" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white hover:shadow-lg transition-all">
          <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          <h4 class="text-xl font-bold mb-2">Gestionar Cursos</h4>
          <p class="text-blue-100 text-sm">Crear, editar y administrar cursos</p>
        </Link>

        <Link :href="route('admin.vehiculos.index')" class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white hover:shadow-lg transition-all">
          <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
          </svg>
          <h4 class="text-xl font-bold mb-2">Gestionar Vehículos</h4>
          <p class="text-green-100 text-sm">Administrar flota de vehículos</p>
        </Link>

        <Link :href="route('admin.inscripciones.index')" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white hover:shadow-lg transition-all">
          <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <h4 class="text-xl font-bold mb-2">Ver Inscripciones</h4>
          <p class="text-purple-100 text-sm">Revisar inscripciones pendientes</p>
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
