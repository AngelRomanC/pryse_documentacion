<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
</script>

<template>
  <Head title="Dashboard Usuario" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Bienvenido al Dashboard de Usuario" main class="mb-8" />
    </SectionMain>
  </LayoutDashboard>
</template>