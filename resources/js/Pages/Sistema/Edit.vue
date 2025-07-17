<script setup>
import { useForm,Link } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import { mdiAbjadHebrew ,mdiBallotOutline, mdiFormatListChecks, mdiOfficeBuilding, mdiFileDocument, mdiMapMarker, mdiCalendar,mdiPlus } from "@mdi/js";
import FormControlV7 from '@/Components/FormControlV7.vue';
import FileUploader from '@/Components/FileUploader.vue';
import CatalogoRedirectButton from '@/Components/CatalogoRedirectButton.vue';

import { ref } from 'vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue';


const isUploading = ref(false)

const props = defineProps({
    titulo: String,
    sistema: Object,
    routeName: String,
    departamentos: Array,
    archivosPrincipales: Array,
});

const archivosPrincipales = ref(props.archivosPrincipales || []);

console.log('Archivos principales:', props.archivosPrincipales);
console.log('Props recibidas:', props);
console.log('Datos del sistema:', props.titulo, props.sistema, props.routeName, props.departamentos);

const form = useForm({
    nombre: props.sistema?.nombre || '',
    descripcion: props.sistema?.descripcion || '',
    departamento_id: props.sistema?.departamento_id || null,
    url: props.sistema?.url || '',
    fecha_creacion: props.sistema?.fecha_creacion || '',
    fecha_produccion: props.sistema?.fecha_produccion || '',
    estatus: props.sistema?.estatus || '',
    numero_usuarios: props.sistema?.numero_usuarios || '',
    nombre_servidor: props.sistema?.nombre_servidor || '',
    ip_servidor: props.sistema?.ip_servidor || '',
    sistema_operativo: props.sistema?.sistema_operativo || '',
    nombre_servidor_bd: props.sistema?.nombre_servidor_bd || '',
    ip_servidor_bd: props.sistema?.ip_servidor_bd || '',
    lenguaje_desarrollo: props.sistema?.lenguaje_desarrollo || '',
    version_lenguaje: props.sistema?.version_lenguaje || '',
    nuevos_documentos_principales: [],
    archivos_a_eliminar: []
});

// Función para mostrar el PDF
const mostrarArchivo = (ruta) => {
    if (!ruta) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'No se ha proporcionado una ruta válida para el archivo PDF.',
        });
        return;
    }
    Swal.fire({
        html: `
            <div style="width: 100%; height: 500px;">
                <iframe src="/storage/${ruta}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
            </div>
        `,
        width: "80%",
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        allowOutsideClick: true, 
        allowEscapeKey: true,
    });
};

// Marcar archivo para eliminación
const marcarParaEliminar = (id) => {
    // Agregar el ID a la lista de archivos a eliminar
    form.archivos_a_eliminar.push(id);
    
    // Filtrar el archivo eliminado de la lista visual
    archivosPrincipales.value = archivosPrincipales.value.filter(a => a.id !== id);
};

const guardar = () => {
    isUploading.value = true;

    const formData = new FormData();

    // Serializar campos excepto archivos nuevos
    Object.entries(form.data()).forEach(([key, value]) => {
        if (key === 'nuevos_documentos_principales' || key === 'archivos_a_eliminar') return;

        if (value !== null && value !== undefined) {
            if (Array.isArray(value)) {
                value.forEach(v => formData.append(`${key}[]`, v));
            } else {
                formData.append(key, value);
            }
        }
    });

    // Archivos nuevos
    form.nuevos_documentos_principales.forEach((file, index) => {
        formData.append(`nuevos_documentos_principales[${index}]`, file);
    });

    // Archivos a eliminar (ids)
    form.archivos_a_eliminar.forEach((id, index) => {
        formData.append(`archivos_a_eliminar[${index}]`, id);
    });

    // PATCH override
    formData.append('_method', 'PATCH');

    router.post(route(`${props.routeName}update`, props.sistema.id), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            isUploading.value = false;
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'El sistema se ha actualizado correctamente.',
            });
        },
        onError: (errors) => {
            isUploading.value = false;
            console.log('Errores de validación:', errors);
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Hubo un problema al actualizar el sistema.',
            });
        }
    });
};
</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main/>
        <CardBox form @submit.prevent="guardar" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Nombre del sistema  -->
                <FormField label="Nombre del sistema" :error="form.errors.nombre">
                    <FormControl
                        v-model="form.nombre"
                        type="text"
                        placeholder="Nombre del sistema "
                        :icon="mdiAbjadHebrew "
                        required
                        class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo"
                    />
                </FormField>
                <!-- Descripcion -->
                <FormField label="Descripcion del sistema" :error="form.errors.descripcion">
                    <FormControl
                        v-model="form.descripcion"
                        type="text"
                        placeholder="Descripcion del sistema "
                        :icon="mdiFormatListChecks"
                        required
                        class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo"
                    />
                </FormField>
                <!-- Departamento -->
                <FormField label="Departamento" :error="form.errors.departamento_id">
                    <FormControl
                                v-model="form.departamento_id"
                                :options="departamentos"
                                type="select"
                                label-key="nombre"
                                value-key="id"
                                :icon="mdiOfficeBuilding"
                                required
                            />
                </FormField>
                <!-- URL del sistema -->
                <FormField label="URL del sistema" :error="form.errors.url">
                    <FormControl
                        v-model="form.url"
                        type="text"
                        placeholder="URL del sistema"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- Fecha de creación --> 
                <FormField label="Fecha de creación" :error="form.errors.fecha_creacion">
                    <FormControl
                        v-model="form.fecha_creacion"
                        type="date"
                        placeholder="Fecha de creación"
                        :icon="mdiCalendar"
                        required
                    />
                </FormField>
                <!-- Fecha de Producción -->
                <FormField label="Fecha de Producción" :error="form.errors.fecha_produccion">
                    <FormControl
                        v-model="form.fecha_produccion"
                        type="date"
                        placeholder="Fecha de publicación"
                        :icon="mdiCalendar"
                        required
                    />
                </FormField>
                <!-- Estatus -->
                <FormField label="Estatus" :error="form.errors.estatus">
                    <FormControl
                        v-model="form.estatus"
                        type="text"
                        :options="$page.props.estatus"
                        placeholder="Selecciona un estatus"
                        label-key="nombre"
                        value-key="id"
                        :icon="mdiMapMarker"
                        required
                    />
                </FormField>
                <!-- Número de usuarios -->
                <FormField label="Número de usuarios" :error="form.errors.numero_usuarios">
                    <FormControl
                        v-model="form.numero_usuarios"
                        type="text"
                        placeholder="Número de usuarios"
                        :icon="mdiFormatListChecks"
                        required
                    />
                </FormField>
                <!-- Nombre del servidor -->
                <FormField label="Nombre del servidor" :error="form.errors.nombre_servidor">
                    <FormControl
                        v-model="form.nombre_servidor"
                        type="text"
                        placeholder="Nombre del servidor"
                        :icon="mdiOfficeBuilding"
                        required
                    />
                </FormField>
                <!-- IP del servidor -->
                <FormField label="IP del servidor" :error="form.errors.ip_servidor">
                    <FormControl
                        v-model="form.ip_servidor"
                        type="text"
                        placeholder="IP del servidor"
                        :icon="mdiMapMarker"
                        required
                    />
                </FormField>
                <!-- Sistema operativo -->
                <FormField label="Sistema operativo" :error="form.errors.sistema_operativo">
                    <FormControl
                        v-model="form.sistema_operativo"
                        type="text"
                        placeholder="Sistema operativo"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- Nombre del servidor de base de datos -->
                <FormField label="Nombre del servidor BD" :error="form.errors.nombre_servidor_bd">
                    <FormControl
                        v-model="form.nombre_servidor_bd"
                        type="text"
                        placeholder="Nombre del servidor BD"
                        :icon="mdiOfficeBuilding"
                        required
                    />
                </FormField>
                <!-- IP del servidor de base de datos -->
                <FormField label="IP del servidor BD" :error="form.errors.ip_servidor_bd">
                    <FormControl
                        v-model="form.ip_servidor_bd"
                        type="text"
                        placeholder="IP del servidor BD"
                        :icon="mdiMapMarker"
                        required
                    />
                </FormField>
                <!-- lenguaje de desarrollo -->
                <FormField label="Lenguaje de desarrollo" :error="form.errors.lenguaje_desarrollo">
                    <FormControl
                        v-model="form.lenguaje_desarrollo"
                        type="text"
                        placeholder="Lenguaje de desarrollo"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- version de lenguaje -->
                <FormField label="Versión de lenguaje" :error="form.errors.version_lenguaje">
                    <FormControl
                        v-model="form.version_lenguaje"
                        type="text"
                        placeholder="Versión de lenguaje"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- Documentos Principales Existentes -->
                <div>
                    <div v-if="archivosPrincipales.length > 0" class="mb-4">
                        <h3 class="text-lg font-medium mb-2">Documentos Principales</h3>
                        <div v-for="archivo in archivosPrincipales" :key="archivo.id" class="flex items-center justify-between p-2 border rounded mb-2">
                            <span class="truncate">{{ archivo.nombre_original }}</span>
                            <div class="flex space-x-2">
                                <BaseButton 
                                    @click="mostrarArchivo(archivo.ruta_archivo)" 
                                    :icon="mdiEye" 
                                    color="info" 
                                    small
                                    title="Ver"
                                />
                                <BaseButton 
                                    @click="marcarParaEliminar(archivo.id)" 
                                    :icon="mdiTrashCan" 
                                    color="danger" 
                                    small
                                    title="Eliminar"
                                />
                            </div>
                        </div>
                    </div>
                    <FileUploader 
                        label="Agregar nuevos documentos principales" 
                        v-model="form.nuevos_documentos_principales"
                        :error="form.errors.nuevos_documentos_principales"
                        accept="application/pdf"
                        multiple
                        @files-selected="agregarDocumentosPrincipales"
                        class="w-full border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-blue-400 transition-colors cursor-pointer"

                        />
                    </div>
            </div>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="guardar" type="submit" color="info" outline label="Crear" />

                    <BaseButton 
                        :href="route(`${routeName}index`)" 
                        type="button" 
                        color="danger" 
                        outline 
                        label="Cancelar"
                    />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>
    <!-- Overlay de carga -->
 <LoadingOverlay :visible="isUploading" title="Subiendo archivo(s)..." subtitle="Por favor, no cierres esta ventana." />

</template>