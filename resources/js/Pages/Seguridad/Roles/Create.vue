<template>
  <Layout>
    <div class="container mx-auto py-6">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold mb-6">{{ titulo }}</h1>

        <form @submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
              Nombre del Rol
            </label>
            <input v-model="form.name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="name"
                   type="text"
                   required>
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Permisos
            </label>
            <div class="grid grid-cols-2 gap-4">
              <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                <input type="checkbox"
                       :id="'permission-' + permission.id"
                       v-model="form.permissions"
                       :value="permission.name"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label :for="'permission-' + permission.id" class="ml-2 block text-sm text-gray-900">
                  {{ permission.name }}
                </label>
              </div>
            </div>
            <div v-if="form.errors.permissions" class="text-red-500 text-xs mt-1">{{ form.errors.permissions }}</div>
          </div>

          <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    :disabled="form.processing">
              Guardar
            </button>
            <Link :href="route(routeName + 'index')"
                  class="text-gray-600 hover:text-gray-800">
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutAuthenticated.vue';

const props = defineProps({
  permissions: Array,
  titulo: String,
  routeName: String
});

const form = useForm({
  name: '',
  permissions: []
});

const submit = () => {
  form.post(route(props.routeName + 'store'));
};
</script>