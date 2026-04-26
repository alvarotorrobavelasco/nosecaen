@extends('layouts.app')

@section('title', 'Gestión de Clientes (AJAX)')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-users"></i> CRUD Clientes con JavaScript (Problema 3.1)</h2>
        <button class="btn btn-primary" onclick="abrirModalCrear()">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tablaClientes" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>CIF</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>País</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCliente">
                    @csrf
                    <input type="hidden" id="clienteId">
                    
                    <div class="mb-3">
                        <label for="cif" class="form-label">CIF *</label>
                        <input type="text" class="form-control" id="cif" maxlength="9" required>
                        <div class="invalid-feedback" id="error-cif"></div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" required>
                        <div class="invalid-feedback" id="error-nombre"></div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono *</label>
                        <input type="text" class="form-control" id="telefono" required>
                        <div class="invalid-feedback" id="error-telefono"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" required>
                        <div class="invalid-feedback" id="error-email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="pais" class="form-label">País *</label>
                        <input type="text" class="form-control" id="pais" required>
                        <div class="invalid-feedback" id="error-pais"></div>
                    </div>
                    
                    <div id="alertaServidor" class="alert alert-danger d-none" role="alert"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCliente()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- CDN: Bootstrap 5, FontAwesome, jQuery, DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    let tabla;
    let modalCliente;

    $(document).ready(function() {
        modalCliente = new bootstrap.Modal(document.getElementById('modalCliente'));
        
        // Inicializar DataTable con AJAX
        tabla = $('#tablaClientes').DataTable({
            ajax: {
                url: '{{ route("clientes.ajax.list") }}',
                dataSrc: '' // Indica que la respuesta es un array directo
            },
            columns: [
                { data: 'cif' },
                { data: 'nombre' },
                { data: 'telefono' },
                { data: 'email' },
                { data: 'pais' },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-warning me-1" onclick="abrirModalEditar(${row.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${row.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
        });
    });

    function abrirModalCrear() {
        limpiarFormulario();
        document.getElementById('modalTitulo').textContent = 'Nuevo Cliente';
        document.getElementById('clienteId').value = '';
        modalCliente.show();
    }

    function abrirModalEditar(id) {
        limpiarFormulario();
        document.getElementById('modalTitulo').textContent = 'Editar Cliente';
        document.getElementById('clienteId').value = id;
        
        // Cargar datos del cliente desde la lista
        fetch('{{ route("clientes.ajax.list") }}')
            .then(res => res.json())
            .then(data => {
                const cliente = data.find(c => c.id == id);
                if(cliente) {
                    document.getElementById('cif').value = cliente.cif;
                    document.getElementById('nombre').value = cliente.nombre;
                    document.getElementById('telefono').value = cliente.telefono;
                    document.getElementById('email').value = cliente.email;
                    document.getElementById('pais').value = cliente.pais;
                    modalCliente.show();
                }
            });
    }

    function guardarCliente() {
        if (!validarCamposJS()) return;

        const id = document.getElementById('clienteId').value;
        const url = id ? `/clientes-ajax/${id}` : '/clientes-ajax';
        const method = id ? 'PUT' : 'POST';

        const datos = {
            _token: csrfToken,
            _method: method,
            cif: document.getElementById('cif').value,
            nombre: document.getElementById('nombre').value,
            telefono: document.getElementById('telefono').value,
            email: document.getElementById('email').value,
            pais: document.getElementById('pais').value
        };

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(async response => {
            if (!response.ok) {
                const errorData = await response.json();
                throw errorData;
            }
            return response.json();
        })
        .then(data => {
            modalCliente.hide();
            tabla.ajax.reload(null, false); // Recarga sin perder paginación/búsqueda
            limpiarFormulario();
        })
        .catch(error => {
            mostrarErroresServidor(error.errors || { general: ['Error inesperado en el servidor'] });
        });
    }

    function eliminarCliente(id) {
        if (!confirm('¿Estás seguro de eliminar este cliente?')) return;

        fetch(`/clientes-ajax/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            tabla.ajax.reload(null, false);
        })
        .catch(err => alert('Error al eliminar'));
    }

    // ✅ VALIDACIÓN JS ANTES DE ENVIAR
    function validarCamposJS() {
        let valido = true;
        limpiarErrores();

        const cif = document.getElementById('cif').value.trim();
        const nombre = document.getElementById('nombre').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const email = document.getElementById('email').value.trim();
        const pais = document.getElementById('pais').value.trim();

        if (!/^[A-Z0-9]{9}$/.test(cif)) {
            marcarError('cif', 'El CIF debe tener exactamente 9 caracteres alfanuméricos.');
            valido = false;
        }
        if (!nombre) {
            marcarError('nombre', 'El nombre es obligatorio.');
            valido = false;
        }
        if (!telefono || telefono.length < 9) {
            marcarError('telefono', 'El teléfono debe tener al menos 9 dígitos.');
            valido = false;
        }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            marcarError('email', 'Introduce un email válido.');
            valido = false;
        }
        if (!pais) {
            marcarError('pais', 'El país es obligatorio.');
            valido = false;
        }

        return valido;
    }

    function marcarError(campo, msg) {
        const el = document.getElementById(campo);
        el.classList.add('is-invalid');
        document.getElementById(`error-${campo}`).textContent = msg;
    }

    function limpiarErrores() {
        document.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
        document.querySelectorAll('[id^="error-"]').forEach(e => e.textContent = '');
        document.getElementById('alertaServidor').classList.add('d-none');
    }

    function mostrarErroresServidor(errors) {
        let html = '<ul class="mb-0">';
        Object.values(errors).flat().forEach(msg => html += `<li>${msg}</li>`);
        html += '</ul>';
        const box = document.getElementById('alertaServidor');
        box.innerHTML = html;
        box.classList.remove('d-none');
    }

    function limpiarFormulario() {
        document.getElementById('formCliente').reset();
        limpiarErrores();
    }
</script>
@endsection