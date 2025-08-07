<script setup>
import { router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import Swal from "sweetalert2";
import { mdiTagEdit, mdiDeleteOutline, mdiInformation, mdiFilePdfBox, mdiPlus, mdiClipboardList } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import SearchBar from '@/components/SearchBar.vue'
import { ref } from 'vue'

import { Link } from '@inertiajs/vue3'


const props = defineProps({
 
  titulo: String,
  routeName: String,
  procesos: Object,
 
});

const filters = ref({
  search: props.filters?.search ?? ''
})

const destroy = (id) => {
  Swal.fire({
    title: "¿Está seguro?",
    text: "Esta acción no se puede revertir",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#d33",
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar registro!",
  }).then((res) => {
    if (res.isConfirmed) {
      router.delete(route(`${props.routeName}destroy`, id));
    }
  });
};


</script>

<template>
  <LayoutMain>
    <SectionTitleLineWithButton :title="props.titulo" main :icon="mdiClipboardList">
     
      <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Registrar Proceso" :icon="mdiPlus" />
    </SectionTitleLineWithButton>

    <SearchBar v-model="filters.search" :routeName="routeName"
      placeholder="Buscar por nombre o departamento..." />

    <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.success }}
    </NotificationBar>

    <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.error }}
    </NotificationBar>

    <CardBox v-if="procesos.data.length < 1">
      <CardBoxComponentEmpty />
    </CardBox>

    <CardBox v-else class="mb-6" has-table>
      

      <Pagination :currentPage="procesos.current_page" :links="procesos.links" :total="procesos.last_page" />

    </CardBox>
  </LayoutMain>
</template>
