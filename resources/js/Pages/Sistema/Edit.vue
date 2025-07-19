<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import { mdiAbjadHebrew, mdiBallotOutline, mdiFormatListChecks, mdiOfficeBuilding, mdiFileDocument, mdiMapMarker, mdiCalendar, mdiPlus, mdiEye, mdiTrashCan } from "@mdi/js";
import FormControlV7 from '@/Components/FormControlV7.vue';
import axios from 'axios';
import FileUploader from '@/Components/FileUploader.vue';
import { ref } from 'vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue';
import Swal from 'sweetalert2';

const isUploading = ref(false);

const props = defineProps({
    titulo: String,
    sistema: Object,
    routeName: String,
    departamentos: Array,
    archivosPrincipales: {
        type: Array,
        default: () => []
    },
});

// Inicialización segura de archivos principales
const archivosPrincipales = ref(
    Array.isArray(props.archivosPrincipales)
        ? props.archivosPrincipales.map(a => ({
            id: a.id || null,
            nombre_original: a.nombre_original || 'Documento sin nombre',
            ruta_archivo: a.ruta_archivo || null
        }))
        : []
);

console.log('archivosPrincipales', archivosPrincipales);

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

const agregarDocumentosPrincipales = (files) => {
    form.nuevos_documentos_principales = [...form.nuevos_documentos_principales, ...Array.from(files)];
};

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

// Modificar la función marcarParaEliminar para manejar correctamente el array
const marcarParaEliminar = (id) => {
    // Asegurarse que archivos_a_eliminar es un array
    if (!Array.isArray(form.archivos_a_eliminar)) {
        form.archivos_a_eliminar = [];
    }
    form.archivos_a_eliminar = [...form.archivos_a_eliminar, id];
    archivosPrincipales.value = archivosPrincipales.value.filter(a => a.id !== id);
};

const guardar = () => {
    // Convertir el Proxy a un array normal
    const archivosIds = [...form.archivos_a_eliminar];
    
    // Crear FormData para enviar archivos y datos
    const formData = new FormData();
    
    // Añadir todos los campos normales
    Object.entries(form.data()).forEach(([key, value]) => {
        if (key !== 'nuevos_documentos_principales' && key !== 'archivos_a_eliminar') {
            formData.append(key, value);
        }
    });
    
    // Añadir archivos nuevos
    form.nuevos_documentos_principales.forEach(file => {
        formData.append('nuevos_documentos_principales[]', file);
    });
    
    // Añadir IDs a eliminar
    archivosIds.forEach(id => {
        formData.append('archivos_a_eliminar[]', id);
    });
    
    isUploading.value = true;
    
    axios.post(route(`${props.routeName}update`, props.sistema.id), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        // Manejo de éxito
    }).catch(error => {
        console.error('Error detallado:', error.response);
        // Manejo de error
    });
};
</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />
        <CardBox form @submit.prevent="guardar" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Nombre del sistema  -->
                <FormField label="Nombre del sistema" :error="form.errors.nombre">
                    <FormControl v-model="form.nombre" type="text" placeholder="Nombre del sistema "
                        :icon="mdiAbjadHebrew" required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Descripcion -->
                <FormField label="Descripcion del sistema" :error="form.errors.descripcion">
                    <FormControl v-model="form.descripcion" type="text" placeholder="Descripcion del sistema "
                        :icon="mdiFormatListChecks" required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Departamento -->
                <FormField label="Departamento" :error="form.errors.departamento_id">
                    <FormControl v-model="form.departamento_id" :options="departamentos" type="select"
                        label-key="nombre" value-key="id" :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- URL del sistema -->
                <FormField label="URL del sistema" :error="form.errors.url">
                    <FormControl v-model="form.url" type="text" placeholder="URL del sistema" :icon="mdiFileDocument"
                        required />
                </FormField>
                <!-- Fecha de creación -->
                <FormField label="Fecha de creación" :error="form.errors.fecha_creacion">
                    <FormControl v-model="form.fecha_creacion" type="date" placeholder="Fecha de creación"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Fecha de Producción -->
                <FormField label="Fecha de Producción" :error="form.errors.fecha_produccion">
                    <FormControl v-model="form.fecha_produccion" type="date" placeholder="Fecha de publicación"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Estatus -->
                <FormField label="Estatus" :error="form.errors.estatus">
                    <FormControl v-model="form.estatus" type="text" :options="$page.props.estatus"
                        placeholder="Selecciona un estatus" label-key="nombre" value-key="id" :icon="mdiMapMarker"
                        required />
                </FormField>
                <!-- Número de usuarios -->
                <FormField label="Número de usuarios" :error="form.errors.numero_usuarios">
                    <FormControl v-model="form.numero_usuarios" type="text" placeholder="Número de usuarios"
                        :icon="mdiFormatListChecks" required />
                </FormField>
                <!-- Nombre del servidor -->
                <FormField label="Nombre del servidor" :error="form.errors.nombre_servidor">
                    <FormControl v-model="form.nombre_servidor" type="text" placeholder="Nombre del servidor"
                        :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- IP del servidor -->
                <FormField label="IP del servidor" :error="form.errors.ip_servidor">
                    <FormControl v-model="form.ip_servidor" type="text" placeholder="IP del servidor"
                        :icon="mdiMapMarker" required />
                </FormField>
                <!-- Sistema operativo -->
                <FormField label="Sistema operativo" :error="form.errors.sistema_operativo">
                    <FormControl v-model="form.sistema_operativo" type="text" placeholder="Sistema operativo"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- Nombre del servidor de base de datos -->
                <FormField label="Nombre del servidor BD" :error="form.errors.nombre_servidor_bd">
                    <FormControl v-model="form.nombre_servidor_bd" type="text" placeholder="Nombre del servidor BD"
                        :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- IP del servidor de base de datos -->
                <FormField label="IP del servidor BD" :error="form.errors.ip_servidor_bd">
                    <FormControl v-model="form.ip_servidor_bd" type="text" placeholder="IP del servidor BD"
                        :icon="mdiMapMarker" required />
                </FormField>
                <!-- lenguaje de desarrollo -->
                <FormField label="Lenguaje de desarrollo" :error="form.errors.lenguaje_desarrollo">
                    <FormControl v-model="form.lenguaje_desarrollo" type="text" placeholder="Lenguaje de desarrollo"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- version de lenguaje -->
                <FormField label="Versión de lenguaje" :error="form.errors.version_lenguaje">
                    <FormControl v-model="form.version_lenguaje" type="text" placeholder="Versión de lenguaje"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- Sección de documentos -->
                <div class="sm:col-span-2">
                    <div v-if="archivosPrincipales.length > 0" class="mb-4">
                        <h3 class="text-lg font-medium mb-2">Documentos Principales</h3>
                        <div v-for="archivo in archivosPrincipales" :key="archivo.id"
                            class="flex items-center justify-between p-2 border rounded mb-2">
                            <span class="truncate">{{ archivo.nombre_original }}</span>
                            <div class="flex space-x-2">
                                <BaseButton @click="mostrarArchivo(archivo.ruta_archivo)" :icon="mdiEye" color="info"
                                    small title="Ver" />
                                <BaseButton @click="marcarParaEliminar(archivo.id)" :icon="mdiTrashCan" color="danger"
                                    small title="Eliminar" />
                            </div>
                        </div>
                    </div>

                    <FormField label="Agregar Nuevos Documentos">
                        <FileUploader v-model="form.nuevos_documentos_principales" accept="application/pdf" multiple
                            @files-selected="agregarDocumentosPrincipales" />
                    </FormField>

                    <div v-if="form.nuevos_documentos_principales.length > 0" class="mt-4">
                        <h4 class="text-sm font-medium mb-2">Nuevos documentos a subir:</h4>
                        <div v-for="(file, index) in form.nuevos_documentos_principales" :key="index"
                            class="flex items-center justify-between p-2 border rounded mb-2">
                            <span class="truncate">{{ file.name }}</span>
                            <BaseButton @click="form.nuevos_documentos_principales.splice(index, 1)" :icon="mdiTrashCan"
                                color="danger" small title="Quitar" />
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="guardar" type="submit" color="info" label="Actualizar"
                        :disabled="form.processing" />
                    <BaseButton :href="route(`${routeName}index`)" type="button" color="danger" outline
                        label="Cancelar" />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>

    <LoadingOverlay :visible="isUploading" title="Subiendo archivo(s)..."
        subtitle="Por favor, no cierres esta ventana." />
</template>