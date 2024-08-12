@extends('masterpage.admin')

@section('title', 'Gestión de Proveedores')

@section('css')
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/3.1.3/bootstrap-notify.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Gestión de Proveedores</h5>
                        </div>
                        <div>
                            <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#providerModal" id="createNewProvider">Crear Nuevo Proveedor</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="providersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Proveedor</th>
                                    <th>Doctor</th>
                                    <th>Contacto</th>
                                    <th>Teléfono</th>
                                    <th>Correo Electrónico</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="providerModal" tabindex="-1" aria-labelledby="providerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="providerModalLabel">Crear Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="providerForm" name="providerForm">
                        <input type="hidden" name="provider_id" id="provider_id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Proveedor</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        @if(Auth::user()->role_id == 1)
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select id="doctor_id" name="doctor_id" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="doctor_id" value="{{ Auth::user()->id }}">
                        @endif

                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Contacto</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/3.1.3/bootstrap-notify.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#providersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('providers.index') }}",
            language: {
                url: 'public/assets/js/es-ES.json'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'doctor.name', name: 'doctor.name' }, // Muestra el nombre del doctor asociado
                { data: 'contact_name', name: 'contact_name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' }, // Nueva columna para dirección
                { data: 'status', name: 'status', render: function(data) {
                    return data ? 'Activo' : 'Inactivo';
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#createNewProvider').click(function () {
            $('#saveBtn').val("create-provider");
            $('#provider_id').val('');
            $('#providerForm').trigger("reset");
            $('#providerModalLabel').text("Crear Nuevo Proveedor");
            $('#providerModal').modal('show');
        });

        $('body').on('click', '.editProvider', function () {
            var providerId = $(this).data('id');
            $.get("{{ url('providers') }}" + '/' + providerId + '/edit', function (data) {
                $('#providerModalLabel').text("Editar Proveedor");
                $('#saveBtn').val("edit-provider");
                $('#providerModal').modal('show');
                $('#provider_id').val(data.id);
                $('#name').val(data.name);
                $('#doctor_id').val(data.doctor_id);
                $('#contact_name').val(data.contact_name);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#address').val(data.address); // Asignar el valor de la dirección
                $('#status').val(data.status);
            });
        });

        $('body').on('submit', '#providerForm', function (e) {
            e.preventDefault();
            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('providers.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#providerForm').trigger("reset");
                    $('#providerModal').modal('hide');
                    table.draw();
                    $.notify({ message: 'Operación exitosa' }, { type: 'success' });
                },
                error: function (data) {
                    $.notify({ message: 'Error en la operación' }, { type: 'danger' });
                }
            });
        });

        $('body').on('click', '.deleteProvider', function () {
            var providerId = $(this).data("id");
            if (confirm("¿Estás seguro de que quieres eliminar este proveedor?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('providers') }}" + '/' + providerId,
                    success: function (data) {
                        table.draw();
                        $.notify({ message: 'Proveedor eliminado' }, { type: 'success' });
                    },
                    error: function (data) {
                        $.notify({ message: 'Error al eliminar el proveedor' }, { type: 'danger' });
                    }
                });
            }
        });
    });
</script>
@endsection
