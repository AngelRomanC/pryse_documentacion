<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutAuthenticated.vue';
import BaseButton from '@/Components/BaseButton.vue';
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { mdiBallotOutline,  } from "@mdi/js";


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

const submit = () => {
    form.post(route(props.routeName + 'store'));
};
</script>

<template>
    <Layout>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" />

            <CardBox is-form @submit.prevent="submit">
                <FormControl
                    v-model="form.name"
                    :error="form.errors.name"
                    label="Nombre"
                    required
                />

                <FormControl
                    v-model="form.description"
                    :error="form.errors.description"
                    type="textarea"
                    label="Descripción"
                    required
                />

                <FormControl
                    v-model="form.module_key"
                    :error="form.errors.module_key"
                    type="select"
                    :options="modules"
                    option-label="name"
                    option-value="key"
                    label="Módulo"
                    required
                />

                <BaseButtons>
                    <BaseButton
                        type="submit"
                        color="info"
                        label="Guardar"
                        :loading="form.processing"
                    />
                    <BaseButton
                        :route-name="routeName + 'index'"
                        color="info"
                        outline
                        label="Cancelar"
                    />
                </BaseButtons>
            </CardBox>
        </SectionMain>
    </Layout>
</template>