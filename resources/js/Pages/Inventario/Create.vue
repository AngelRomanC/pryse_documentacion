<script setup>
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlSelect from "@/components/FormControlSelect.vue";

import {
  mdiBallotOutline,
  mdiAccount,
  mdiHomeCity,
  mdiCalendar,
  mdiDesktopClassic,
  mdiMicrosoftWindows,
  mdiChip,
  mdiMemory,
  mdiHarddisk,
  mdiUsb,
  mdiCamera,
  mdiCommentTextOutline
} from "@mdi/js";

const props = defineProps({
  titulo: String,
  routeName: String,
  departamentos: Array,
});

const form = useForm({
  fecha_registro: '',
  nombre_persona: '',
  departamento_id: '',
  tipo_pc: '',
  marca_equipo: '',
  sistema_operativo: '',
  procesador: '',
  tarjeta_madre: '',
  tarjeta_grafica: '',
  datos_tarjeta_grafica: '',
  tipo_ram: '',
  capacidad_ram: '',
  marca_ram: '',
  tipo_disco: '',
  capacidad_disco: '',
  teclado_mouse: '',
  camara_web: '',
  otro_periferico: '',
  nombre_arqueo: '',
  observaciones: '',
});

const handleSubmit = () => {
  form.post(route(`${props.routeName}store`));
};


</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

    <CardBox form @submit.prevent="handleSubmit">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <FormField label="Fecha de Registro" :error="form.errors.fecha_registro">
          <FormControl v-model="form.fecha_registro" type="datetime-local" :icon="mdiCalendar" required />
        </FormField>

        <FormField label="Nombre de Persona" :error="form.errors.nombre_persona">
          <FormControl v-model="form.nombre_persona" type="text" :icon="mdiAccount" required />
        </FormField>

        <FormField label="Área" :error="form.errors.departamentos">
          <FormControl v-model="form.departamento_id" :options="departamentos" type="select" :icon="mdiHomeCity" required />
        </FormField>




        <FormField label="Tipo de PC" :error="form.errors.tipo_pc">
          <FormControl v-model="form.tipo_pc" type="text" :icon="mdiDesktopClassic" required />
        </FormField>

<FormField label="Tipo de PC" :error="form.errors.tipo_pc">
  <FormControlSelect
    v-model="form.tipo_pc"
    type="select"
    :icon="mdiDesktopClassic"
    :options="[
      { value: '', text: 'Elige' },
      { value: 'Laptop', text: 'Laptop' },
      { value: 'PC Escritorio', text: 'PC Escritorio' },
      { value: 'PC Escritorio (Servidor)', text: 'PC Escritorio (Servidor)' },
      { value: 'Impresora', text: 'Impresora' }
    ]"
    required
  />
</FormField>





        <FormField label="Marca del Equipo" :error="form.errors.marca_equipo">
          <FormControl v-model="form.marca_equipo" type="text" :icon="mdiDesktopClassic" required />
        </FormField>

        <FormField label="Sistema Operativo" :error="form.errors.sistema_operativo">
          <FormControl v-model="form.sistema_operativo" type="text" :icon="mdiMicrosoftWindows" required />
        </FormField>

        <FormField label="Procesador" :error="form.errors.procesador">
          <FormControl v-model="form.procesador" type="text" :icon="mdiChip" required />
        </FormField>

        <FormField label="Tarjeta Madre" :error="form.errors.tarjeta_madre">
          <FormControl v-model="form.tarjeta_madre" type="text" :icon="mdiChip" required />
        </FormField>

        <FormField label="Tarjeta Gráfica" :error="form.errors.tarjeta_grafica">
          <FormControl v-model="form.tarjeta_grafica" type="text" :icon="mdiChip" />
        </FormField>

        <FormField label="Datos Tarjeta Gráfica" :error="form.errors.datos_tarjeta_grafica">
          <FormControl v-model="form.datos_tarjeta_grafica" type="text" :icon="mdiChip" />
        </FormField>

        <FormField label="Tipo de RAM" :error="form.errors.tipo_ram">
          <FormControl v-model="form.tipo_ram" type="text" :icon="mdiMemory" required />
        </FormField>

        <FormField label="Capacidad RAM" :error="form.errors.capacidad_ram">
          <FormControl v-model="form.capacidad_ram" type="text" :icon="mdiMemory" required />
        </FormField>

        <FormField label="Marca de RAM" :error="form.errors.marca_ram">
          <FormControl v-model="form.marca_ram" type="text" :icon="mdiMemory" required />
        </FormField>

        <FormField label="Tipo de Disco" :error="form.errors.tipo_disco">
          <FormControl v-model="form.tipo_disco" type="text" :icon="mdiHarddisk" required />
        </FormField>

        <FormField label="Capacidad Disco" :error="form.errors.capacidad_disco">
          <FormControl v-model="form.capacidad_disco" type="text" :icon="mdiHarddisk" required />
        </FormField>

        <FormField label="Teclado y Mouse" :error="form.errors.teclado_mouse">
          <FormControl v-model="form.teclado_mouse" type="text" :icon="mdiUsb" required />
        </FormField>

        <FormField label="Cámara Web" :error="form.errors.camara_web">
          <FormControl v-model="form.camara_web" type="text" :icon="mdiCamera" />
        </FormField>

        <FormField label="Otro Periférico" :error="form.errors.otro_periferico">
          <FormControl v-model="form.otro_periferico" type="text" :icon="mdiUsb" />
        </FormField>

        <FormField label="Nombre Arqueo" :error="form.errors.nombre_arqueo">
          <FormControl v-model="form.nombre_arqueo" type="text" :icon="mdiAccount" required />
        </FormField>

        <FormField label="Observaciones" :error="form.errors.observaciones">
          <FormControl v-model="form.observaciones" type="text" :icon="mdiCommentTextOutline" />
        </FormField>
      </div>

      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
