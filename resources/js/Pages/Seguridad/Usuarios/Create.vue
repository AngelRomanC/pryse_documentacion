<script>
import { Link, useForm } from '@inertiajs/vue3';
import {
  mdiBallotOutline,
  mdiAccount,
  mdiAccountCircle,
  mdiAccountTie,
  mdiPhone,
  mdiMail,
  mdiLock
} from "@mdi/js";

import LayoutMain from '@/layouts/LayoutMain.vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";

export default {
  props: {
    titulo: { type: String, required: true },
    routeName: { type: String, required: true },
    roles: { type: Object, required: true },
  },
  components: {
    LayoutMain,
    FormField,
    FormControl,
    BaseDivider,
    BaseButton,
    BaseButtons,
    CardBox,
    SectionTitleLineWithButton
  },
  setup() {
    const handleSubmit = () => {
      form.post(route('usuarios.store'));
    };

    const form = useForm({
      name: '',
      apellido_paterno: '',
      apellido_materno: '',
      numero: '',
      email: '',
      password: '',
      role: 'Admin',
    });

    return {
      handleSubmit,
      form,
      mdiBallotOutline,
      mdiAccount,
      mdiAccountCircle,
      mdiAccountTie,
      mdiPhone,
      mdiMail,
      mdiLock
    };
  }
}
</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>
      <a :href="`${route(routeName + 'index')}`">
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
          pattern="^\d{10}$"
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

      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Crear" />
          <BaseButton :href="route(`${routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
