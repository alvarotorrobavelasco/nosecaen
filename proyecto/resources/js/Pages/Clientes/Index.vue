<template>
    <div class="container py-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="fas fa-users me-2"></i>Clientes (Vue + Vite + Inertia)</h5>
          <button class="btn btn-light btn-sm fw-bold" @click="abrirModal()">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
          </button>
        </div>
        
        <div class="card-body">
          <!-- Buscador -->
          <div class="row mb-3">
            <div class="col-md-4">
              <input v-model="busqueda" type="text" class="form-control" placeholder="🔍 Buscar cliente...">
            </div>
          </div>
  
          <!-- Tabla -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>CIF</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>País</th>
                  <th>Cuota</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="c in clientesFiltrados" :key="c.id">
                  <td><span class="badge bg-secondary">{{ c.cif }}</span></td>
                  <td class="fw-bold">{{ c.nombre }}</td>
                  <td>{{ c.email }}</td>
                  <td>{{ c.pais }}</td>
                  <td>{{ c.cuota_mensual || 0 }} €</td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-warning me-1" @click="editar(c)" title="Editar">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" @click="eliminar(c.id)" title="Eliminar">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr v-if="clientesFiltrados.length === 0">
                  <td colspan="6" class="text-center text-muted py-4">No se encontraron clientes</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Modal -->
      <div class="modal fade show d-block" v-if="modal" tabindex="-1" style="background: rgba(0,0,0,0.4)">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">{{ editando ? 'Editar' : 'Nuevo' }} Cliente</h5>
              <button type="button" class="btn-close btn-close-white" @click="cerrarModal()"></button>
            </div>
            <div class="modal-body">
              <!-- ✅ CIF con validación estricta -->
              <div class="mb-3">
                <label class="form-label">CIF / DNI *</label>
                <input 
                  v-model="form.cif" 
                  class="form-control" 
                  maxlength="9"
                  placeholder="Ej: 12345678A"
                  :class="{'is-invalid': errores.cif}"
                  @input="errores.cif = ''"
                >
                <div class="invalid-feedback">{{ errores.cif }}</div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input v-model="form.nombre" class="form-control" :class="{'is-invalid': errores.nombre}" @input="errores.nombre = ''">
                <div class="invalid-feedback">{{ errores.nombre }}</div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Teléfono *</label>
                  <input v-model="form.telefono" class="form-control" :class="{'is-invalid': errores.telefono}" @input="errores.telefono = ''">
                  <div class="invalid-feedback">{{ errores.telefono }}</div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email *</label>
                  <input v-model="form.email" type="email" class="form-control" :class="{'is-invalid': errores.email}" @input="errores.email = ''">
                  <div class="invalid-feedback">{{ errores.email }}</div>
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">País *</label>
                <input v-model="form.pais" class="form-control" :class="{'is-invalid': errores.pais}" @input="errores.pais = ''">
                <div class="invalid-feedback">{{ errores.pais }}</div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Moneda *</label>
                  <select v-model="form.moneda" class="form-select" :class="{'is-invalid': errores.moneda}" @change="errores.moneda = ''">
                    <option value="EUR">EUR - Euro</option>
                    <option value="USD">USD - Dólar</option>
                    <option value="GBP">GBP - Libra</option>
                  </select>
                  <div class="invalid-feedback">{{ errores.moneda }}</div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Cuota Mensual *</label>
                  <input v-model.number="form.cuota_mensual" type="number" step="0.01" class="form-control" :class="{'is-invalid': errores.cuota_mensual}" @input="errores.cuota_mensual = ''">
                  <div class="invalid-feedback">{{ errores.cuota_mensual }}</div>
                </div>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button class="btn btn-secondary" @click="cerrarModal()">Cancelar</button>
              <button class="btn btn-primary px-4" @click="guardar()" :disabled="cargando">
                <span v-if="cargando" class="spinner-border spinner-border-sm me-2"></span>
                {{ cargando ? 'Guardando...' : 'Guardar' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  
  const props = defineProps({ clientes: Array });
  const modal = ref(false);
  const editando = ref(false);
  const cargando = ref(false);
  const busqueda = ref('');
  const errores = reactive({});
  
  const form = reactive({ 
    id: null, 
    cif: '', 
    nombre: '', 
    telefono: '', 
    email: '', 
    pais: '', 
    moneda: 'EUR', 
    cuota_mensual: 0 
  });
  
  const clientesFiltrados = computed(() => {
    if (!props.clientes) return []; 
    if (!busqueda.value) return props.clientes;
    const term = busqueda.value.toLowerCase();
    return props.clientes.filter(c => 
      (c.nombre && c.nombre.toLowerCase().includes(term)) || 
      (c.cif && c.cif.toLowerCase().includes(term)) || 
      (c.email && c.email.toLowerCase().includes(term))
    );
  });
  
  const limpiarErrores = () => {
    Object.keys(errores).forEach(k => errores[k] = '');
  };
  
  const abrirModal = () => {
    limpiarErrores(); 
    Object.assign(form, { id: null, cif: '', nombre: '', telefono: '', email: '', pais: '', moneda: 'EUR', cuota_mensual: 0 }); 
    editando.value = false; 
    modal.value = true;
  };
  
  const editar = (c) => {
    limpiarErrores(); 
    Object.assign(form, c); 
    editando.value = true; 
    modal.value = true;
  };
  
  const cerrarModal = () => { 
    modal.value = false; 
    limpiarErrores(); 
  };
  
  // ✅ VALIDACIÓN CORREGIDA DEL CIF/DNI
  const validar = () => {
    limpiarErrores(); 
    let ok = true;
    
    // CIF/DNI: Obligatorio y exactamente 9 caracteres
    if (!form.cif || form.cif.trim() === '') {
      errores.cif = 'El CIF/DNI es obligatorio';
      ok = false;
    } else if (form.cif.trim().length !== 9) {
      errores.cif = 'Debe tener exactamente 9 caracteres';
      ok = false;
    }
    
    if (!form.nombre || form.nombre.trim() === '') {
      errores.nombre = 'Campo obligatorio';
      ok = false;
    }
    if (!form.telefono || form.telefono.trim().length < 9) {
      errores.telefono = 'Mínimo 9 dígitos';
      ok = false;
    }
    if (!form.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      errores.email = 'Email inválido';
      ok = false;
    }
    if (!form.pais || form.pais.trim() === '') {
      errores.pais = 'Campo obligatorio';
      ok = false;
    }
    if (!form.moneda) {
      errores.moneda = 'Selecciona una moneda';
      ok = false;
    }
    if (form.cuota_mensual < 0) {
      errores.cuota_mensual = 'No puede ser negativo';
      ok = false;
    }
    
    return ok;
  };
  
  const guardar = async () => {
    // ✅ Si la validación falla, NO se envía y se muestran los errores
    if (!validar()) {
      return;
    }
    
    cargando.value = true;
    try {
      if (editando.value) {
        router.put(`/clientes-vue-vite/${form.id}`, { ...form });
      } else {
        router.post('/clientes-vue-vite', { ...form });
      }
      cerrarModal();
    } catch (e) {
      if (e.response?.status === 422) {
        const errs = e.response.data.errors;
        Object.keys(errs).forEach(campo => {
          errores[campo] = Array.isArray(errs[campo]) ? errs[campo][0] : errs[campo];
        });
      }
    } finally { 
      cargando.value = false; 
    }
  };
  
  const eliminar = async (id) => {
    if (!confirm('¿Estás seguro de eliminar este cliente?')) return;
    router.delete(`/clientes-vue-vite/${id}`);
  };
  </script>
  
  <style scoped>
  .modal { display: block; }
  .table th { font-weight: 600; color: #495057; }
  .badge { font-size: 0.85rem; }
  </style>