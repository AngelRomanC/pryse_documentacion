<script setup>
import { useForm } from '@inertiajs/vue3'
import {
  mdiBallotOutline,
  mdiAccount,
  mdiAccountCircle,
  mdiAccountTie,
  mdiPhone,
  mdiMail,
  mdiLock,
  mdiShieldAccount,
} from "@mdi/js"

import LayoutMain from '@/layouts/LayoutMain.vue'
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import FormControlCheckbox from "@/components/FormControlCheckbox.vue" // ✅ Importar el checkbox
import BaseDivider from "@/components/BaseDivider.vue"
import BaseButton from "@/components/BaseButton.vue"
import BaseButtons from "@/components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"

// ✅ props con <script setup>
const props = defineProps({
  titulo: { type: String, required: true },
  routeName: { type: String, required: true },
  roles: { type: Array, required: true },
})

// ✅ useForm inicializado con objeto para múltiples roles
const form = useForm({
  name: '',
  apellido_paterno: '',
  apellido_materno: '',
  numero: '',
  email: '',
  password: '',
  roles: {}, // Objeto para almacenar selección de roles
})

// ✅ Inicializar el objeto de roles
// Esto crea una propiedad por cada rol: { '1': false, '2': false, ... }
const initializeRoles = () => {
  props.roles.forEach(role => {
    form.roles[role.id] = false
  })
}

// Llamar la inicialización
initializeRoles()

// ✅ submit con inertia - convertir el objeto a array de IDs
const handleSubmit = () => {
  // Convertir el objeto de roles a array de IDs seleccionados
  const selectedRoles = Object.keys(form.roles)
    .filter(roleId => form.roles[roleId])
    .map(roleId => parseInt(roleId))
  
  form.transform((data) => ({
    ...data,
    roles: selectedRoles // Enviar como array
  })).post(route(`${props.routeName}store`))
}
</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>
      <a :href="route(`${routeName}index`)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
             class="bi bi-x" viewBox="0 0 16 16">
          <path
            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
        </svg>
      </a>
    </SectionTitleLineWithButton>

    <CardBox form @submit.prevent="handleSubmit">
      <FormField :error="form.errors.name">
        <FormControl
          v-model="form.name"
          required
          placeholder="Nombre"
          :icon="mdiAccount"
        />
      </FormField>

      <FormField :error="form.errors.apellido_paterno">
        <FormControl
          v-model="form.apellido_paterno"
          required
          placeholder="Apellido paterno"
          :icon="mdiAccountCircle"
        />
      </FormField>

      <FormField :error="form.errors.apellido_materno">
        <FormControl
          v-model="form.apellido_materno"
          required
          placeholder="Apellido materno"
          :icon="mdiAccountTie"
        />
      </FormField>

      <FormField :error="form.errors.numero">
        <FormControl
          v-model="form.numero"
          required
          placeholder="Teléfono"
          type="tel"
          maxlength="10"
          pattern="^\\d{10}$"
          title="El número debe contener exactamente 10 dígitos"
          :icon="mdiPhone"
        />
      </FormField>

      <FormField :error="form.errors.email">
        <FormControl
          v-model="form.email"
          required
          placeholder="Correo electrónico"
          :icon="mdiMail"
        />
      </FormField>

      <FormField :error="form.errors.password">
        <FormControl
          v-model="form.password"
          type="password"
          required
          placeholder="Contraseña"
          :icon="mdiLock"
        />
      </FormField>

      <!-- Sección de Selección Múltiple de Roles - Mismo patrón que marcas -->
      <BaseDivider />

      <FormField label="Asignar Roles" :error="form.errors.roles">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          <label 
            v-for="role in roles" 
            :key="role.id" 
            class="inline-flex items-center space-x-2"
          >
            <FormControlCheckbox v-model="form.roles[role.id]" />
            <span class="capitalize">{{ role.name }}</span>
          </label>
        </div>

        <!-- Mostrar roles seleccionados (opcional) -->
        <div 
          v-if="Object.values(form.roles).some(selected => selected)" 
          class="mt-4 p-3 bg-blue-50 rounded-lg"
        >
          <p class="text-sm font-medium text-blue-800">Roles seleccionados:</p>
          <div class="flex flex-wrap gap-2 mt-2">
            <span 
              v-for="role in roles" 
              :key="role.id"
              v-if="form.roles[role.id]"
              class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"
            >
              {{ role.name }}
            </span>
          </div>
        </div>
      </FormField>

      <template #footer>
        <BaseButtons>
          <BaseButton
            @click="handleSubmit"
            type="submit"
            color="info"
            outline
            label="Crear"
            :disabled="form.processing"
          />
          <BaseButton
            :href="route(`${routeName}index`)"
            type="reset"
            color="danger"
            outline
            label="Cancelar"
          />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>