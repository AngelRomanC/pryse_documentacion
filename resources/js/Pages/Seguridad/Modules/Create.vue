<script setup>
import { useForm } from '@inertiajs/vue3'
import LayoutMain from '@/layouts/LayoutMain.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from "@/components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import { mdiBallotOutline } from "@mdi/js"

const props = defineProps({
    titulo: String,
    routeName: String
})

const form = useForm({
    name: '',
    key: '',
    description: ''
})

const submit = () => {
    form.post(route(props.routeName + 'store'))
}
</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

        <CardBox is-form @submit.prevent="submit">
            <FormField label="Nombre" :error="form.errors.name">
                <FormControl v-model="form.name" type="text" required />
            </FormField>

            <FormField label="Clave" :error="form.errors.key">
                <FormControl v-model="form.key" type="text" required />
            </FormField>

            <FormField label="Descripción" :error="form.errors.description">
                <FormControl v-model="form.description" type="textarea" />
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
                    color="info"
                    outline
                    label="Cancelar"
                />
            </BaseButtons>
        </CardBox>
    </LayoutMain>
</template>