@extends('layouts.app')

@section('title', 'Clientes Vue')

@section('content')
<!-- CSS Quasar y Fuentes -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quasar@2.16.0/dist/quasar.prod.css" rel="stylesheet">

<style>
    [v-cloak] { display: none; }
    
    /* Forzar alineación del encabezado */
    .header-container {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 20px;
        width: 100%;
    }

    /* Botones de la tabla */
    .action-buttons {
        display: inline-flex;
        gap: 5px;
    }
</style>

<div id="app-quasar" v-cloak>
    <div class="q-pa-md">
        
        <!-- ✅ ENCABEZADO ARREGLADO: Título a la izquierda, Botón a la derecha -->
        <div class="header-container">
            <h4 class="q-ma-none text-primary">
                <q-icon name="people" class="q-mr-sm"></q-icon>
                Clientes (Vue + Quasar)
            </h4>
            <q-btn color="primary" icon="add" label="Nuevo Cliente" @click="abrirModalCrear" />
        </div>

        <!-- Tabla -->
        <q-card>
            <q-table
                title="Listado de Clientes"
                :rows="clientes"
                :columns="columns"
                row-key="id"
                :loading="loading"
                :filter="filter"
                :pagination="{ rowsPerPage: 10 }"
                flat
                bordered
            >
                <template v-slot:top-right>
                    <!-- ✅ BUSCADOR CON ANCHO FIJO PARA QUE NO SE CORTE -->
                    <q-input
                        filled
                        dense
                        debounce="300"
                        v-model="filter"
                        placeholder="Buscar cliente..."
                        class="q-mr-sm"
                        style="width: 250px"
                    >
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                </template>

                <!-- Columna Acciones -->
                <template v-slot:body-cell-acciones="props">
                    <q-td :props="props">
                        <div class="action-buttons">
                            <q-btn 
                                unelevated 
                                size="sm" 
                                color="primary" 
                                icon="edit" 
                                @click="abrirModalEditar(props.row)"
                            >
                                <q-tooltip>Editar</q-tooltip>
                            </q-btn>
                            <q-btn 
                                unelevated 
                                size="sm" 
                                color="red" 
                                icon="delete" 
                                @click="confirmarEliminar(props.row)"
                            >
                                <q-tooltip>Eliminar</q-tooltip>
                            </q-btn>
                        </div>
                    </q-td>
                </template>
            </q-table>
        </q-card>

        <!-- Modal Crear/Editar -->
        <q-dialog v-model="modalAbierto" persistent>
            <q-card style="min-width: 400px; max-width: 500px;">
                <q-card-section class="bg-primary text-white">
                    <div class="text-h6">
                        <q-icon name="person" class="q-mr-sm"></q-icon>
                        <span v-text="modoEdicion ? 'Editar Cliente' : 'Nuevo Cliente'"></span>
                    </div>
                </q-card-section>

                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-12">
                            <q-input 
                                filled 
                                v-model="form.cif" 
                                label="CIF *" 
                                maxlength="9"
                                :error="errores.cif.length > 0"
                                :error-message="errores.cif"
                            />
                        </div>
                        <div class="col-12">
                            <q-input 
                                filled 
                                v-model="form.nombre" 
                                label="Nombre *"
                                :error="errores.nombre.length > 0"
                                :error-message="errores.nombre"
                            />
                        </div>
                        <div class="col-6">
                            <q-input 
                                filled 
                                v-model="form.telefono" 
                                label="Teléfono *"
                                :error="errores.telefono.length > 0"
                                :error-message="errores.telefono"
                            />
                        </div>
                        <div class="col-6">
                            <q-input 
                                filled 
                                v-model="form.email" 
                                label="Email *" 
                                type="email"
                                :error="errores.email.length > 0"
                                :error-message="errores.email"
                            />
                        </div>
                        <div class="col-12">
                            <q-input 
                                filled 
                                v-model="form.pais" 
                                label="País *"
                                :error="errores.pais.length > 0"
                                :error-message="errores.pais"
                            />
                        </div>
                    </div>

                    <q-banner v-if="errorServidor" class="bg-negative text-white q-mt-md">
                        <ul class="q-ma-none q-pl-md">
                            <li v-for="(msgs, campo) in erroresServidor" :key="campo" v-text="msgs[0]"></li>
                        </ul>
                    </q-banner>
                </q-card-section>

                <!-- Botones del modal con separación forzada -->
                <div style="display: flex !important; justify-content: flex-end !important; gap: 10px; padding: 16px;">
                    <q-btn flat label="Cancelar" color="grey" v-close-popup />
                    <q-btn flat label="Guardar" color="primary" @click="guardar" :loading="guardando" />
                </div>
            </q-card>
        </q-dialog>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/vue@3.4.21/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quasar@2.16.0/dist/quasar.umd.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// Configurar Axios con CSRF token
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content || 
                                                   document.querySelector('input[name="_token"]')?.value || '';
axios.defaults.headers.common['Accept'] = 'application/json';

document.addEventListener('DOMContentLoaded', function() {
    const { createApp, ref, reactive, onMounted } = window.Vue;
    const { Notify, Dialog } = window.Quasar;

    createApp({
        setup() {
            const clientes = ref([]);
            const loading = ref(false);
            const filter = ref('');
            const modalAbierto = ref(false);
            const modoEdicion = ref(false);
            const guardando = ref(false);
            const errorServidor = ref(false);
            
            const form = reactive({
                id: null,
                cif: '',
                nombre: '',
                telefono: '',
                email: '',
                pais: ''
            });
            
            const errores = reactive({
                cif: '',
                nombre: '',
                telefono: '',
                email: '',
                pais: ''
            });
            
            const erroresServidor = ref({});

            const columns = [
                { name: 'cif', label: 'CIF', field: 'cif', align: 'left', sortable: true },
                { name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left', sortable: true },
                { name: 'telefono', label: 'Teléfono', field: 'telefono' },
                { name: 'email', label: 'Email', field: 'email' },
                { name: 'pais', label: 'País', field: 'pais' },
                { name: 'acciones', label: 'Acciones', field: 'acciones', sortable: false, style: 'width: 100px' }
            ];

            const cargarClientes = async () => {
                loading.value = true;
                try {
                    const res = await axios.get('/clientes-ajax/list');
                    clientes.value = res.data;
                } catch (e) {
                    Notify.create({ type: 'negative', message: 'Error al cargar clientes' });
                } finally {
                    loading.value = false;
                }
            };

            const limpiarErrores = () => {
                errores.cif = '';
                errores.nombre = '';
                errores.telefono = '';
                errores.email = '';
                errores.pais = '';
                errorServidor.value = false;
                erroresServidor.value = {};
            };

            const abrirModalCrear = () => {
                form.id = null;
                form.cif = '';
                form.nombre = '';
                form.telefono = '';
                form.email = '';
                form.pais = '';
                limpiarErrores();
                modoEdicion.value = false;
                modalAbierto.value = true;
            };

            const abrirModalEditar = (cliente) => {
                form.id = cliente.id;
                form.cif = cliente.cif || '';
                form.nombre = cliente.nombre || '';
                form.telefono = cliente.telefono || '';
                form.email = cliente.email || '';
                form.pais = cliente.pais || '';
                limpiarErrores();
                modoEdicion.value = true;
                modalAbierto.value = true;
            };

            const validarFormulario = () => {
                let valido = true;
                limpiarErrores();

                if (!form.cif || form.cif.trim().length !== 9) {
                    errores.cif = 'El CIF debe tener 9 caracteres';
                    valido = false;
                }
                if (!form.nombre || form.nombre.trim() === '') {
                    errores.nombre = 'El nombre es obligatorio';
                    valido = false;
                }
                if (!form.telefono || form.telefono.trim().length < 9) {
                    errores.telefono = 'Mínimo 9 dígitos';
                    valido = false;
                }
                if (!form.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
                    errores.email = 'Email inválido';
                    valido = false;
                }
                if (!form.pais || form.pais.trim() === '') {
                    errores.pais = 'El país es obligatorio';
                    valido = false;
                }

                return valido;
            };

            const guardar = async () => {
                if (!validarFormulario()) {
                    Notify.create({ type: 'warning', message: 'Corrige los errores' });
                    return;
                }

                guardando.value = true;
                limpiarErrores();

                try {
                    const url = form.id ? '/clientes-ajax/' + form.id : '/clientes-ajax';
                    const method = form.id ? 'put' : 'post';
                    
                    const payload = {
                        cif: form.cif,
                        nombre: form.nombre,
                        telefono: form.telefono,
                        email: form.email,
                        pais: form.pais
                    };
                    
                    if (form.id) {
                        payload._method = 'PUT';
                    }

                    await axios.post(url, payload);
                    
                    Notify.create({ 
                        type: 'positive', 
                        message: form.id ? 'Cliente actualizado' : 'Cliente creado' 
                    });
                    modalAbierto.value = false;
                    await cargarClientes();
                } catch (e) {
                    if (e.response?.status === 422) {
                        errorServidor.value = true;
                        erroresServidor.value = e.response.data.errors || {};
                        
                        const errors = e.response.data.errors;
                        if (errors.cif) errores.cif = errors.cif[0];
                        if (errors.nombre) errores.nombre = errors.nombre[0];
                        if (errors.telefono) errores.telefono = errors.telefono[0];
                        if (errors.email) errores.email = errors.email[0];
                        if (errors.pais) errores.pais = errors.pais[0];
                        
                        Notify.create({ type: 'warning', message: 'Errores de validación' });
                    } else {
                        Notify.create({ type: 'negative', message: 'Error al guardar' });
                    }
                } finally {
                    guardando.value = false;
                }
            };

            const confirmarEliminar = (cliente) => {
                Dialog.create({
                    title: 'Confirmar',
                    message: '¿Eliminar cliente "' + cliente.nombre + '"?',
                    cancel: true,
                    persistent: true
                }).onOk(async () => {
                    try {
                        await axios.delete('/clientes-ajax/' + cliente.id);
                        Notify.create({ type: 'positive', message: 'Cliente eliminado' });
                        await cargarClientes();
                    } catch (e) {
                        Notify.create({ type: 'negative', message: 'Error al eliminar' });
                    }
                });
            };

            onMounted(cargarClientes);

            return {
                clientes, loading, filter, columns,
                modalAbierto, modoEdicion, guardando, errorServidor,
                form, errores, erroresServidor,
                abrirModalCrear, abrirModalEditar, guardar, confirmarEliminar
            };
        }
    })
    .use(window.Quasar, { plugins: { Notify, Dialog } })
    .mount('#app-quasar');
});
</script>
@endsection