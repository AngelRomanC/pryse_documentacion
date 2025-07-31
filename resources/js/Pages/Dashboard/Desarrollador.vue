<template>
  <Head title="Dashboard Desarrollador" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Panel del Desarrollador" main class="mb-6" />
    </SectionMain>

    <!-- Tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <CardBox>
        <h3 class="text-sm font-medium text-gray-500">Sistemas Asignados</h3>
        <p class="text-2xl font-semibold text-gray-900">{{ sistemasAsignados }}</p>
      </CardBox>
      <CardBox>
        <h3 class="text-sm font-medium text-gray-500">En Desarrollo</h3>
        <p class="text-2xl font-semibold text-blue-600">{{ sistemasEnDesarrollo }}</p>
      </CardBox>
      <CardBox>
        <h3 class="text-sm font-medium text-gray-500">Incidencias Abiertas</h3>
        <p class="text-2xl font-semibold text-red-600">{{ incidenciasAbiertas }}</p>
      </CardBox>
    </div>

    <!-- Sistemas asignados -->
    <CardBox>
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Mis Sistemas</h2>
      </div>
      <div class="divide-y divide-gray-200">
        <div v-for="sistema in sistemasAsignadosLista" :key="sistema.id" class="p-4 hover:bg-gray-50">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-medium text-gray-900">{{ sistema.nombre }}</h3>
              <span class="ml-2 text-sm text-gray-500">{{ sistema.estatus }}</span>
            </div>
            <span class="text-xs text-gray-500">{{ new Date(sistema.updated_at).toLocaleDateString() }}</span>
          </div>
        </div>
      </div>
    </CardBox>
  </LayoutDashboard>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";

const props = defineProps({
  sistemasAsignados: Number,
  sistemasEnDesarrollo: Number,
  incidenciasAbiertas: Number,
  sistemasAsignadosLista: Array
});
</script>
