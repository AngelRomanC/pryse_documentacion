<script setup>
import { router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import Swal from "sweetalert2";
import { mdiTagEdit, mdiDeleteOutline, mdiInformation, mdiMagnify, mdiFilterVariant } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import moment from "moment";
import { ref, watch } from 'vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import SearchBar from '@/components/SearchBar.vue'

const props = defineProps({
    titulo: String,
    sistemas: Object,
    routeName: String,
    filters: Object,
});
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
const filters = ref({ ...props.filters })
</script>

<script>
export default {
    methods: {
        showFileUploadAlert() {
        Swal.fire({
            title: 'Subir archivo Excel',
            html: `
            <input type="file" id="excelFile" class="swal2-file" accept=".xlsx, .xls, .csv">
            <p class="text-sm text-gray-500 mt-2">Por favor seleccione un archivo Excel</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
            const fileInput = document.getElementById('excelFile');
            const file = fileInput.files[0];
            
            if (!file) {
                Swal.showValidationMessage('Por favor seleccione un archivo');
                return false;
            }
            
            // Validar que sea un archivo Excel
            const validExtensions = ['.xlsx', '.xls', '.csv'];
            const fileName = file.name.toLowerCase();
            const isValidFile = validExtensions.some(ext => fileName.endsWith(ext));
            
            if (!isValidFile) {
                Swal.showValidationMessage('Por favor suba un archivo Excel válido (.xlsx, .xls, .csv)');
                return false;
            }
            
            // Aquí puedes manejar la subida del archivo
            return this.uploadFile(file);
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
            Swal.fire(
                'Éxito!',
                'El archivo ha sido subido correctamente.',
                'success'
            );
            }
        });
        },
        
        async uploadFile(file) {
        try {
            const formData = new FormData();
            formData.append('excel_file', file);
            
            // Reemplaza con tu endpoint real
            const response = await axios.post(route('tu.ruta.de.subida'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
            });
            
            return response.data;
        } catch (error) {
            Swal.showValidationMessage(
            `Error al subir el archivo: ${error.response?.data?.message || error.message}`
            );
            return false;
        }
        }
    }
    }
</script>
<template>
    <LayoutMain>
        <SectionTitleLineWithButton :title="props.titulo" main>
            <BaseButton 
                @click="showFileUploadAlert" 
                color="danger" 
                label="Cargar Datos" 
            />
            <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Crear" />
        </SectionTitleLineWithButton>
        
        <SearchBar   v-model="filters.search"  :routeName="routeName"  placeholder="Buscar sistema por nombre..."  />

        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.success }}
        </NotificationBar>

        <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.error }}
        </NotificationBar>

     

        <CardBox v-if="sistemas.data.length < 1">
            <CardBoxComponentEmpty />
        </CardBox>

        <CardBox v-else class="mb-6" has-table>
            <table class="w-full border-collapse border mt-4">
                <thead>
                    <tr>
                        <th />
                        <th class="border p-2">Nombre de Sistema</th>                                              
                        <th class="border p-2">Departamento</th>                        
                        <th class="border p-2">URL</th>
                        <th class="border p-2">Estatus</th>
                        <th class="border p-2">Acciones</th>                       
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="documento in sistemas.data" :key="documento.id">
                        <td class="align-items-center"></td>
                        <td data-label="Nombre de Sistema" class="border p-2">{{documento.nombre}}</td>        
                        <td data-label="Departamento" class="border p-2">{{ documento.departamento.nombre}}</td>
                        <td data-label="URL del sistema " class="border p-2">{{ documento.url}} </td> 
                        <td data-label="Estatus del sistema " class="border p-2">{{ documento.estatus}} </td>        
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton color="info" :icon="mdiTagEdit" small
                                    :href="route(`${props.routeName}edit`, documento.id)" />
                                <BaseButton color="danger" :icon="mdiDeleteOutline" small
                                    @click="destroy(documento.id)" />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :currentPage="sistemas.current_page" :links="sistemas.links"
                :total="sistemas.links.length - 2" />
        </CardBox>
    </LayoutMain>
</template>