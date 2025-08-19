<template>
  <Layout>
    <div class="container mx-auto py-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">{{ titulo }}</h1>
        <Link :href="route(routeName + 'create')" 
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Nuevo Rol
        </Link>
      </div>

      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombre
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Permisos
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="role in roles" :key="role.id">
              <td class="px-6 py-4 whitespace-nowrap">{{ role.name }}</td>
              <td class="px-6 py-4">
                <span v-for="permission in role.permissions" 
                      :key="permission.id"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-1">
                  {{ permission.name }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <Link :href="route(routeName + 'edit', role.id)"
                      class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                <button @click="deleteRole(role)"
                        class="text-red-600 hover:text-red-900">Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/layouts/LayoutAuthenticated.vue'; // Corregido: ruta correcta al layout
import { router } from '@inertiajs/vue3';

const props = defineProps({
  roles: Array,
  titulo: String,
  routeName: String
});

const deleteRole = (role) => {
  if (confirm('¿Estás seguro de eliminar este rol?')) {
    router.delete(route(props.routeName + 'destroy', role.id));
  }
};
</script>