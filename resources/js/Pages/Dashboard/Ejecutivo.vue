<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import LayoutMain from '@/layouts/LayoutMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionMain from '@/components/SectionMain.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import {
  mdiChartLine,
  mdiCalendarClock,
  mdiCheckCircle,
  mdiAlertCircle,
  mdiAccountGroup,
  mdiFileDocument,
  mdiTrendingUp,
  mdiClockFast
} from "@mdi/js"

// Datos que podrías recibir del backend
const props = defineProps({
  metrics: Object,
  recentProcesses: Array,
  statusSummary: Object,
  upcomingDeadlines: Array
})

// Métricas principales
const mainMetrics = ref([
  {
    title: 'Total de Procesos',
    value: props.metrics?.total_processes || 0,
    icon: mdiFileDocument,
    color: 'blue',
    trend: '+12%'
  },
  {
    title: 'En Progreso',
    value: props.metrics?.in_progress || 0,
    icon: mdiClockFast,
    color: 'yellow',
    trend: '+5%'
  },
  {
    title: 'Completados',
    value: props.metrics?.completed || 0,
    icon: mdiCheckCircle,
    color: 'green',
    trend: '+8%'
  },
  {
    title: 'Vencidos',
    value: props.metrics?.overdue || 0,
    icon: mdiAlertCircle,
    color: 'red',
    trend: '-3%'
  }
])

// Ir a la lista de procesos
const goToProcesses = () => {
  router.visit(route('procesos.index'))
}

// Ver detalles de un proceso
const viewProcess = (processId) => {
  router.visit(route('procesos.show', processId))
}
</script>

<template>
  <LayoutMain title="Dashboard Ejecutivo">
    <SectionMain>
      <SectionTitleLineWithButton 
        :icon="mdiChartLine" 
        title="Dashboard Ejecutivo" 
        main 
      >
        <BaseButton
          :route-name="'procesos.index'"
          color="info"
          outline
          label="Ver Todos los Procesos"
          :icon="mdiFileDocument"
        />
      </SectionTitleLineWithButton>

      <!-- Tarjetas de Métricas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <CardBox 
          v-for="(metric, index) in mainMetrics" 
          :key="index"
          class="hover:shadow-lg transition-shadow duration-200"
        >
          <div class="flex items-start">
            <div :class="`bg-${metric.color}-100 p-3 rounded-full mr-4`">
              <svg class="w-8 h-8" :class="`text-${metric.color}-600`" viewBox="0 0 24 24">
                <path :d="metric.icon" fill="currentColor" />
              </svg>
            </div>
            <div>
              <h3 class="text-sm font-medium text-gray-500">{{ metric.title }}</h3>
              <p class="text-2xl font-semibold text-gray-900">{{ metric.value }}</p>
              <p class="text-xs mt-1" :class="`text-${metric.color}-500`">
                {{ metric.trend }} vs mes anterior
              </p>
            </div>
          </div>
        </CardBox>
      </div>

      <!-- Grid de 2 columnas -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Procesos Recientes -->
        <CardBox>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Procesos Recientes</h3>
            <BaseButton
              @click="goToProcesses"
              color="info"
              outline
              small
              label="Ver todos"
            />
          </div>
          
          <div class="space-y-3">
            <div 
              v-for="process in recentProcesses" 
              :key="process.id"
              class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer"
              @click="viewProcess(process.id)"
            >
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ process.nombre }}</p>
                <p class="text-sm text-gray-500">{{ process.departamento?.nombre }}</p>
              </div>
              <span 
                class="px-2 py-1 text-xs rounded-full"
                :class="{
                  'bg-green-100 text-green-800': process.estatus === 'Completado',
                  'bg-yellow-100 text-yellow-800': process.estatus === 'En Progreso',
                  'bg-red-100 text-red-800': process.estatus === 'Vencido'
                }"
              >
                {{ process.estatus }}
              </span>
            </div>
            
            <p v-if="!recentProcesses?.length" class="text-gray-500 text-center py-4">
              No hay procesos recientes
            </p>
          </div>
        </CardBox>

        <!-- Próximos Vencimientos -->
        <CardBox>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Próximos Vencimientos</h3>
            <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24">
              <path :d="mdiCalendarClock" fill="currentColor" />
            </svg>
          </div>
          
          <div class="space-y-3">
            <div 
              v-for="deadline in upcomingDeadlines" 
              :key="deadline.id"
              class="p-3 border rounded-lg hover:bg-yellow-50"
            >
              <div class="flex justify-between items-start">
                <div class="flex-1">
                  <p class="font-medium text-gray-900">{{ deadline.nombre }}</p>
                  <p class="text-sm text-gray-500">{{ deadline.departamento?.nombre }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-red-600">
                    {{ new Date(deadline.fecha_fin_vigencia).toLocaleDateString() }}
                  </p>
                  <p class="text-xs text-gray-500">Vence en {{ deadline.days_remaining }} días</p>
                </div>
              </div>
            </div>
            
            <p v-if="!upcomingDeadlines?.length" class="text-gray-500 text-center py-4">
              No hay vencimientos próximos
            </p>
          </div>
        </CardBox>

        <!-- Resumen por Estado -->
        <CardBox>
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribución por Estado</h3>
          <div class="space-y-2">
            <div 
              v-for="(count, status) in statusSummary" 
              :key="status"
              class="flex items-center justify-between"
            >
              <span class="text-sm text-gray-600">{{ status }}</span>
              <span class="font-semibold">{{ count }}</span>
            </div>
          </div>
        </CardBox>

        <!-- Resumen por Departamento -->
        <CardBox>
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Procesos por Departamento</h3>
          <div class="space-y-2">
            <div 
              v-for="dept in props.metrics?.by_department || []" 
              :key="dept.id"
              class="flex items-center justify-between"
            >
              <span class="text-sm text-gray-600">{{ dept.nombre }}</span>
              <span class="font-semibold">{{ dept.process_count }}</span>
            </div>
          </div>
        </CardBox>
      </div>
    </SectionMain>
  </LayoutMain>
</template>