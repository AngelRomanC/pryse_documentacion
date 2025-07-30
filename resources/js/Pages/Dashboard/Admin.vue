<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import { ref } from "vue";

const props = defineProps({
  sistemasCount: Number,
  equiposCount: Number,
  equiposPorTipo: Object,
  sistemasPorEstatus: Object,
  ultimosEquipos: Array,
  sistemasRecientes: Array
});
</script>

<template>
  <Head title="Dashboard Admin" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Bienvenido al Dashboard de Administrador" main class="mb-8" />
    </SectionMain>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <CardBox class="bg-blue-50">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-blue-800">Sistemas Registrados</h3>
            <p class="text-3xl font-bold">{{ sistemasCount }}</p>
          </div>
          <svg class="w-10 h-10 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"/>
          </svg>
        </div>
      </CardBox>

      <CardBox class="bg-green-50">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-green-800">Equipos Registrados</h3>
            <p class="text-3xl font-bold">{{ equiposCount }}</p>
          </div>
          <svg class="w-10 h-10 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
          </svg>
        </div>
      </CardBox>

      <CardBox class="bg-purple-50">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-purple-800">Laptops</h3>
            <p class="text-3xl font-bold">{{ equiposPorTipo?.Laptop || 0 }}</p>
          </div>
          <svg class="w-10 h-10 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
          </svg>
        </div>
      </CardBox>

      <CardBox class="bg-orange-50">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-orange-800">Sistemas en Producción</h3>
            <p class="text-3xl font-bold">{{ sistemasPorEstatus?.Producción || 0 }}</p>
          </div>
          <svg class="w-10 h-10 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
          </svg>
        </div>
      </CardBox>
    </div>

    <!-- Sección de Últimos Registros -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <CardBox>
        <h3 class="text-lg font-semibold mb-4">Últimos Equipos Registrados</h3>
        <div class="space-y-4">
          <div v-for="equipo in ultimosEquipos" :key="equipo.id" class="border-b pb-2">
            <p class="font-medium">{{ equipo.nombre_persona }}</p>
            <p class="text-sm text-gray-600">{{ equipo.tipo_pc }} - {{ equipo.marca_equipo }}</p>
            <p class="text-xs text-gray-500">{{ equipo.fecha_registro }}</p>
          </div>
        </div>
      </CardBox>

      <CardBox>
        <h3 class="text-lg font-semibold mb-4">Sistemas Recientes</h3>
        <div class="space-y-4">
          <div v-for="sistema in sistemasRecientes" :key="sistema.id" class="border-b pb-2">
            <p class="font-medium">{{ sistema.nombre }}</p>
            <p class="text-sm text-gray-600">{{ sistema.departamento }} - {{ sistema.estatus }}</p>
            <p class="text-xs text-gray-500">{{ sistema.fecha_creacion }}</p>
          </div>
        </div>
      </CardBox>
    </div>
  </LayoutDashboard>
</template>