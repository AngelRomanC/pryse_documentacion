<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import LayoutMain from '@/layouts/LayoutMain.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from "@/components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import FormControlSelect from "@/components/FormControlSelect.vue"
import Accordion from '@/components/Accordion.vue'

import {
  mdiBallotOutline,
  mdiAccount,
  mdiHomeCity,
  mdiDesktopClassic,
  mdiMicrosoftWindows,
  mdiChip,
  mdiMemory,
  mdiHarddisk,
  mdiUsb,
  mdiCamera,
  mdiCommentTextOutline, 
  mdiBriefcaseAccount,
} from "@mdi/js";

const props = defineProps({
  titulo: String,
  routeName: String,
  
});



const handleSubmit = () => {
  form.post(route(`${props.routeName}store`));
};


</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

    <CardBox form @submit.prevent="handleSubmit" class="space-y-4">

      
      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>

