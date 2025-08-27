<script setup>
import { defineProps, computed } from 'vue'
import LayoutMain from '@/layouts/LayoutMain.vue'
import CardBox from "@/components/CardBox.vue"

// Props del controlador
const props = defineProps({
  titulo: String,
  stats: Object,
  porEstatus: Object,
  proximosProcesos: Array,
  procesos: Array,
})

// Función para darle color a los estatus
const estatusColor = (estatus) => {
  switch (estatus) {
    case 'Diseño':
      return 'bg-yellow-100 text-yellow-800'
    case 'Revisión':
      return 'bg-blue-100 text-blue-800'
    case 'Validación':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

// Preparar datos para gráfico tipo barra (opcional si usas Chart.js o Vue Chart 3)
const chartData = computed(() => ({
  labels: Object.keys(props.porEstatus),
  datasets: [
    {
      label: 'Procesos por estatus',
      data: Object.values(props.porEstatus),
      backgroundColor: ['#FACC15','#3B82F6','#10B981']
    }
  ]
}))
</script>

<template>
  <LayoutMain :title="titulo">
    <h1 class="text-3xl font-bold mb-6">{{ titulo }}</h1>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <CardBox class="p-4 bg-blue-50 shadow hover:shadow-lg transition">
        <div class="text-center">
          <p class="text-gray-600 font-medium">Total de Procesos</p>
          <p class="text-3xl font-bold text-blue-700">{{ stats.total || 0 }}</p>
        </div>
      </CardBox>

      <CardBox class="p-4 bg-yellow-50 shadow hover:shadow-lg transition">
        <div class="text-center">
          <p class="text-gray-600 font-medium">Pendientes</p>
          <p class="text-3xl font-bold text-yellow-700">{{ stats.pendientes || 0 }}</p>
        </div>
      </CardBox>

      <CardBox class="p-4 bg-green-50 shadow hover:shadow-lg transition">
        <div class="text-center">
          <p class="text-gray-600 font-medium">Con Documentos</p>
          <p class="text-3xl font-bold text-green-700">{{ stats.con_documentos || 0 }}</p>
        </div>
      </CardBox>

      <CardBox class="p-4 bg-red-50 shadow hover:shadow-lg transition">
        <div class="text-center">
          <p class="text-gray-600 font-medium">Sin Documentos</p>
          <p class="text-3xl font-bold text-red-700">{{ stats.sin_documentos || 0 }}</p>
        </div>
      </CardBox>
    </div>

    <!-- Próximos procesos a entregar -->
    <CardBox class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Próximos procesos a VENCER en (30 días)</h2>
      <ul>
        <li v-for="proceso in proximosProcesos" :key="proceso.id" class="py-2 border-b flex justify-between">
          <span>{{ proceso.nombre }}</span>
          <span class="text-gray-500">{{ proceso.fecha_fin_vigencia }}</span>
        </li>
        <li v-if="proximosProcesos.length === 0" class="text-gray-400 italic">No hay procesos próximos a Vencer</li>
      </ul>
    </CardBox>

    <!-- Tabla de procesos con estatus -->
    <CardBox class="overflow-x-auto">
      <h2 class="text-xl font-semibold mb-4">Procesos del Departamento</h2>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Departamento</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estatus</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha Entrega</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Documentos</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="proceso in procesos" :key="proceso.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 font-medium text-gray-700">{{ proceso.nombre }}</td>
            <td class="px-6 py-4 text-gray-600">{{ proceso.departamento?.nombre }}</td>
            <td class="px-6 py-4">
              <span :class="['px-3 py-1 rounded-full text-xs font-semibold', estatusColor(proceso.estatus)]">
                {{ proceso.estatus }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-600">{{ proceso.fecha_entrega }}</td>
            <td class="px-6 py-4 text-gray-700">
              {{ proceso.archivos.length }} archivo(s)
            </td>
          </tr>
        </tbody>
      </table>
    </CardBox>

    <!-- Gráfico de estatus -->
    <CardBox class="mt-8 p-4">
      <h2 class="text-xl font-semibold mb-4">Distribución por Estatus</h2>
      <div v-if="Object.keys(porEstatus).length > 0" class="h-64 flex items-center justify-center">
        <!-- Aquí puedes integrar tu librería de gráficos preferida -->
        <!-- Ejemplo con Chart.js o Vue Chart 3 -->
        <canvas id="chartEstatus"></canvas>
      </div>
      <div v-else class="text-gray-400 italic">No hay datos para mostrar</div>
    </CardBox>
  </LayoutMain>
</template>
