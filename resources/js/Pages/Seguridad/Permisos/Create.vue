<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutAuthenticated.vue';
import BaseButton from '@/Components/BaseButton.vue';
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import FormField from "@/Components/FormField.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { mdiBallotOutline } from "@mdi/js";

const props = defineProps({
    modules: Array,
    titulo: String,
    routeName: String
});

const form = useForm({
    name: '',
    guard_name: 'web',
    description: '',
    module_key: ''
});
// Debug: ver qué módulos llegan al frontend
console.log('Módulos recibidos:', props.modules);
const submit = () => {  
    form.post(route(props.routeName + 'store'))
};
</script>

<template>
    <Layout>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" />

            <CardBox is-form @submit.prevent="submit">

                <!-- Nombre -->
                <FormField label="Nombre" :error="form.errors.name">
                    <FormControl
                        v-model="form.name"
                        required
                    />
                </FormField>

                <!-- Descripción -->
                <FormField label="Descripción" :error="form.errors.description">
                    <FormControl
                        v-model="form.description"
                        type="textarea"
                        required
                    />
                </FormField>

                <!-- Módulo -->
                <FormField label="Módulo al que pertenecé" :error="form.errors.module_key">
                    <FormControl
                        v-model="form.module_key"
                        type="select"
                        :options="modules"
                        option-label="name"
                        option-value="id"
                        required
                    />
                </FormField>

                <BaseButtons>
                    <BaseButton
                        type="submit"
                        color="info"
                        label="Guardar"
                        :loading="form.processing"
                    />
                    <BaseButton
                        :route-name="routeName + 'index'"
                        color="danger"
                        outline
                        label="Cancelar"
                    />
                </BaseButtons>
            </CardBox>
        </SectionMain>
    </Layout>
</template>
