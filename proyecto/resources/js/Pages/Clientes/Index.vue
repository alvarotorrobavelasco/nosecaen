<template>
    <div class="container mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Clientes (Vue + Vite + Inertia)</h3>
        <button class="btn btn-primary" @click="abrirModal()">Nuevo</button>
      </div>
      <table class="table table-bordered table-striped bg-white">
        <thead><tr><th>CIF</th><th>Nombre</th><th>Email</th><th>País</th><th>Cuota</th><th>Acciones</th></tr></thead>
        <tbody>
          <tr v-for="c in clientes" :key="c.id">
            <td>{{c.cif}}</td><td>{{c.nombre}}</td><td>{{c.email}}</td><td>{{c.pais}}</td><td>{{c.importe_cuota_mensual}}€</td>
            <td>
              <button class="btn btn-sm btn-warning me-1" @click="editar(c)">Editar</button>
              <button class="btn btn-sm btn-danger" @click="eliminar(c.id)">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Modal -->
      <div class="modal fade show d-block" v-if="modal" tabindex="-1" style="background:rgba(0,0,0,0.5)">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">{{editando?'Editar':'Nuevo'}} Cliente</h5><button class="btn-close" @click="cerrarModal()"></button></div>
            <div class="modal-body">
              <div class="mb-2"><label>CIF</label><input v-model="form.cif" class="form-control" :class="{'is-invalid':errores.cif}"><div class="invalid-feedback">{{errores.cif}}</div></div>
              <div class="mb-2"><label>Nombre</label><input v-model="form.nombre" class="form-control" :class="{'is-invalid':errores.nombre}"><div class="invalid-feedback">{{errores.nombre}}</div></div>
              <div class="mb-2"><label>Teléfono</label><input v-model="form.telefono" class="form-control" :class="{'is-invalid':errores.telefono}"><div class="invalid-feedback">{{errores.telefono}}</div></div>
              <div class="mb-2"><label>Email</label><input v-model="form.email" type="email" class="form-control" :class="{'is-invalid':errores.email}"><div class="invalid-feedback">{{errores.email}}</div></div>
              <div class="mb-2"><label>País</label><input v-model="form.pais" class="form-control" :class="{'is-invalid':errores.pais}"><div class="invalid-feedback">{{errores.pais}}</div></div>
              <div class="row"><div class="col-6 mb-2"><label>Moneda</label><input v-model="form.moneda" class="form-control" :class="{'is-invalid':errores.moneda}"><div class="invalid-feedback">{{errores.moneda}}</div></div><div class="col-6 mb-2"><label>Cuota</label><input v-model.number="form.importe_cuota_mensual" type="number" class="form-control" :class="{'is-invalid':errores.importe_cuota_mensual}"><div class="invalid-feedback">{{errores.importe_cuota_mensual}}</div></div></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" @click="cerrarModal()">Cancelar</button><button class="btn btn-primary" @click="guardar()" :disabled="cargando">{{cargando?'Guardando...':'Guardar'}}</button></div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import {ref, reactive} from 'vue';
  import {router} from '@inertiajs/vue3';
  const props = defineProps({clientes: Array});
  const modal = ref(false), editando = ref(false), cargando = ref(false), errores = reactive({});
  const form = reactive({id:null, cif:'', nombre:'', telefono:'', email:'', pais:'', moneda:'EUR', importe_cuota_mensual:0});
  const limpiarErrores = () => Object.keys(errores).forEach(k => errores[k]='');
  const abrirModal = () => {limpiarErrores(); Object.assign(form,{id:null,cif:'',nombre:'',telefono:'',email:'',pais:'',moneda:'EUR',importe_cuota_mensual:0}); editando.value=false; modal.value=true;};
  const editar = (c) => {limpiarErrores(); Object.assign(form,c); editando.value=true; modal.value=true;};
  const cerrarModal = () => {modal.value=false; limpiarErrores();};
  const validar = () => {
    limpiarErrores(); let ok=true;
    if(!form.cif||form.cif.length!==9){errores.cif='9 caracteres';ok=false;}
    if(!form.nombre){errores.nombre='Obligatorio';ok=false;}
    if(!form.telefono||form.telefono.length<9){errores.telefono='Mín 9 dígitos';ok=false;}
    if(!form.email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)){errores.email='Email inválido';ok=false;}
    if(!form.pais){errores.pais='Obligatorio';ok=false;}
    if(!form.moneda){errores.moneda='Obligatorio';ok=false;}
    if(form.importe_cuota_mensual<0){errores.importe_cuota_mensual='No negativo';ok=false;}
    return ok;
  };
  const guardar = async () => {
    if(!validar()) return; cargando.value=true;
    try {
      if(editando.value) router.put(`/clientes-vue-vite/${form.id}`, {...form});
      else router.post('/clientes-vue-vite', {...form});
      cerrarModal();
    } catch(e) {
      if(e.response?.status===422) {
        const errs=e.response.data.errors;
        Object.keys(errs).forEach(c=>errores[c]=Array.isArray(errs[c])?errs[c][0]:errs[c]);
      }
    } finally {cargando.value=false;}
  };
  const eliminar = async (id) => {if(!confirm('¿Eliminar?')) return; router.delete(`/clientes-vue-vite/${id}`);};
  </script>