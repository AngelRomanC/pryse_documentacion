<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import CardBox from '@/components/CardBox.vue';
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from '@/components/BaseButtons.vue';
import FormField from '@/components/FormField.vue';
import FormControlSelect from '@/components/FormControlSelect.vue';
import FormControl from '@/components/FormControl.vue';
import { mdiBallotOutline } from "@mdi/js";

const props = defineProps({
    permissions: Array,
    titulo: String,
    routeName: String
});

const form = useForm({
    name: '',
    permissions: []
});

const submit = () => {
    form.post(route(props.routeName + 'store'));
};
</script>

<template>
<LayoutMain>
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

    <CardBox >
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Nombre del Rol -->
            <FormField label="Nombre del Rol" :error="form.errors.name">
                <FormControl 
                    v-model="form.name" 
                    placeholder="Ingrese el nombre del rol" 
                    required 
                />
            </FormField>

            <!-- Permisos -->
            <FormField label="Permisos" :error="form.errors.permissions">
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="permission in props.permissions" :key="permission.id" class="flex items-center">
                        <input 
                            type="checkbox" 
                            :id="'permission-' + permission.id" 
                            v-model="form.permissions" 
                            :value="permission.name" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label :for="'permission-' + permission.id" class="ml-2 block text-sm text-gray-900">
                            {{ permission.name }}
                        </label>
                    </div>
                </div>
            </FormField>

            <!-- Botones -->
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                <BaseButton 
                    type="submit"
                    color="info"
                    label="Guardar"
                    :loading="form.processing"
                />
                <BaseButton
                    :href="route(props.routeName + 'index')"
                    color="gray"
                    outline
                    label="Cancelar"
                />
            </BaseButtons>
        </form>
    </CardBox>
</LayoutMain>
</template>
