<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
</script>

<template>
  <Head title="Dashboard Admin" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Bienvenido al Dashboard de Administrador" main class="mb-8" />
    </SectionMain>
  </LayoutDashboard>
</template>