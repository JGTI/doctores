@extends('masterpage.admin')

@section('title', 'Gestión de Información de Pacientes')

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
                            <h5 class="card-title fw-semibold">Gestión de Información de Pacientes</h5>
                        </div>
                        <div>
                            <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#patientModal" id="createNewPatient">Crear Nueva Información de Paciente</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="patientsTable">
                            <thead>
                                <tr>
                                    <th></th> <!-- Para el botón de expandir -->
                                    <th>ID</th>
                                    <th>Nombre del Paciente</th>
                                    <th>Teléfono</th>
                                    <th>CURP</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Género</th>
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
    <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Crear Nueva Información de Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="patientForm" name="patientForm">
                        <input type="hidden" name="patient_id" id="patient_id">
                        <div class="mb-3">
                            <label for="patient_name" class="form-label">Nombre del Paciente</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name" required>
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
                            <label for="curp" class="form-label">CURP</label>
                            <input type="text" class="form-control" id="curp" name="curp" >
                        </div>
                        <div class="mb-3">
                            <label for="rfc" class="form-label">RFC</label>
                            <input type="text" class="form-control" id="rfc" name="rfc" >
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="dob" name="dob">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Género</label>
                            <select id="gender" name="gender" class="form-select">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medical_history" class="form-label">Historial Médico</label>
                            <textarea class="form-control" id="medical_history" name="medical_history"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="allergies" class="form-label">Alergias</label>
                            <textarea class="form-control" id="allergies" name="allergies"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="current_medication" class="form-label">Medicamentos Actuales</label>
                            <textarea class="form-control" id="current_medication" name="current_medication"></textarea>
                        </div>
                        


                        @if(Auth::user()->role_id == 1)
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor a Cargo</label>
                            <select id="doctor_id" name="doctor_id" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="doctor_id" value="{{ Auth::user()->id }}">
                        @endif


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
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.1/js/selectize.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // Función para formatear los detalles adicionales (como un subformato)
        function format(d) {
            return '<table class="table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                    '<td>RFC:</td>' +
                    '<td>' + (d.rfc ? d.rfc : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Correo Electrónico:</td>' +
                    '<td>' + (d.email ? d.email : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Dirección:</td>' +
                    '<td>' + (d.address ? d.address : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Historial Médico:</td>' +
                    '<td>' + (d.medical_history ? d.medical_history : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Alergias:</td>' +
                    '<td>' + (d.allergies ? d.allergies : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Medicamentos Actuales:</td>' +
                    '<td>' + (d.current_medication ? d.current_medication : 'N/A') + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Doctor a Cargo:</td>' +
                    '<td>' + (d.doctor_name ? d.doctor_name : 'N/A') + '</td>' +
                '</tr>' +
            '</table>';
        }

        // Inicializa Selectize para el select de doctor
        $('#doctor_id').selectize({
            placeholder: 'Selecciona un doctor',
            create: false // No permite crear nuevas opciones
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#patientsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('patients.index') }}",
            language: {
                url: 'public/assets/js/es-ES.json'
            },
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { data: 'id', name: 'id' },
                { data: 'patient_name', name: 'patient_name' },
                { data: 'phone', name: 'phone' },
                { data: 'curp', name: 'curp' },
                { data: 'dob', name: 'dob' },
                { data: 'gender', name: 'gender' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[1, 'asc']]
        });

        // Controlador de detalles de fila
        $('#patientsTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // Si los detalles están mostrando, esconderlos
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Mostrar los detalles
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        $('#createNewPatient').click(function () {
            $('#saveBtn').val("create-patient");
            $('#patient_id').val('');
            $('#patientForm').trigger("reset");
            $('#patientModalLabel').text("Crear Nueva Información de Paciente");
            $('#patientModal').modal('show');
        });

        $('body').on('click', '.editPatient', function () {
            var patientId = $(this).data('id');
            $.get("{{ url('patients') }}" + '/' + patientId + '/edit', function (data) {
                $('#patientModalLabel').text("Editar Información de Paciente");
                $('#saveBtn').val("edit-patient");
                $('#patientModal').modal('show');
                $('#patient_id').val(data.id);
                $('#patient_name').val(data.patient_name);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#curp').val(data.curp);
                $('#rfc').val(data.rfc);
                $('#address').val(data.address);
                $('#dob').val(data.dob);
                $('#gender').val(data.gender);
                $('#medical_history').val(data.medical_history);
                $('#allergies').val(data.allergies);
                $('#current_medication').val(data.current_medication);
                $('#doctor_id').val(data.doctor_id);
            });
        });

        $('body').on('submit', '#patientForm', function (e) {
            e.preventDefault();
            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('patients.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#patientForm').trigger("reset");
                    $('#patientModal').modal('hide');
                    table.draw();
                    $.notify({ message: 'Operación exitosa' }, { type: 'success' });
                },
                error: function (data) {
                    $.notify({ message: 'Error en la operación' }, { type: 'danger' });
                }
            });
        });

        $('body').on('click', '.deletePatient', function () {
            var patientId = $(this).data("id");
            if (confirm("¿Estás seguro de que quieres eliminar esta información de paciente?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('patients') }}" + '/' + patientId,
                    success: function (data) {
                        table.draw();
                        $.notify({ message: 'Información de paciente eliminada' }, { type: 'success' });
                    },
                    error: function (data) {
                        $.notify({ message: 'Error al eliminar la información del paciente' }, { type: 'danger' });
                    }
                });
            }
        });
    });
</script>
@endsection
