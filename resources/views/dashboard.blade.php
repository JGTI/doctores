@extends('masterpage.admin')

@section('title', 'Gestión de Información del Doctor')

@section('css')
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css">
@endsection

@section('content')

    @php
        $doctor = \App\Models\DoctorDetails::where('user_id', Auth::user()->id)->first();
    @endphp

    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">


                    @if(Auth::user()->role_id==5)
                        <h5 class="card-title fw-semibold mb-4">{{ $doctor ? 'Actualizar' : 'Crear' }} Información del Doctor</h5>

                        <form id="doctorForm" method="POST">
                            @csrf
                            @if($doctor)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="specialty" class="form-label">Especialidad</label>
                                <input type="text" class="form-control" id="specialty" name="specialty" value="{{ $doctor->specialty ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="license_number" class="form-label">Número de Cédula Profesional</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" value="{{ $doctor->license_number ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="joining_date" class="form-label">Fecha de Egreso</label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ $doctor->joining_date ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="office_address" class="form-label">Dirección del Consultorio</label>
                                <input type="text" class="form-control" id="office_address" name="office_address" value="{{ $doctor->office_address ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="office_hours" class="form-label">Horario de Atención</label>
                                <div class="d-flex">
                                    @php
                                        $hours = [
                                            '07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', 
                                            '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', 
                                            '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', 
                                            '04:00 PM', '04:30 PM', '05:00 PM', '05:30 PM', '06:00 PM', '06:30 PM', 
                                            '07:00 PM', '07:30 PM', '08:00 PM', '08:30 PM', '09:00 PM', '09:30 PM',
                                            '10:00 PM', '10:30 PM', '11:00 PM', '11:30 PM', '12:00 AM', '12:30 AM',
                                            '01:00 AM', '01:30 AM', '02:00 AM', '02:30 AM', '03:00 AM', '03:30 AM', 
                                            '04:00 AM', '04:30 AM', '05:00 AM', '05:30 AM', '06:00 AM', '06:30 AM', 
                                        ];

                                        $startTime = '';
                                        $endTime = '';

                                        if (isset($doctor) && strpos($doctor->office_hours, ' - ') !== false) {
                                            list($startTime, $endTime) = explode(' - ', $doctor->office_hours);
                                        }
                                    @endphp
                                    
                                    <select class="form-select me-2" id="office_start" name="office_start">
                                        <option value="">Hora de Inicio</option>
                                        @foreach($hours as $hour)
                                            <option value="{{ $hour }}" {{ $startTime == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                                        @endforeach
                                    </select>

                                    <select class="form-select" id="office_end" name="office_end">
                                        <option value="">Hora de Cierre</option>
                                        @foreach($hours as $hour)
                                            <option value="{{ $hour }}" {{ $endTime == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="state" class="form-label">Estado de la República</label>
                                <select class="form-select" id="state" name="state">
                                    <option value="">Selecciona un estado</option>
                                    @php
                                        $states = [
                                            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                                            'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 
                                            'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 
                                            'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 
                                            'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 
                                            'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 
                                            'Yucatán', 'Zacatecas'
                                        ];
                                    @endphp
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ (isset($doctor) && $doctor->state == $state) ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="mb-3">
                                <label for="additional_contact_info" class="form-label">Información Adicional de Contacto</label>
                                <textarea class="form-control" id="additional_contact_info" name="additional_contact_info">{{ $doctor->additional_contact_info ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $doctor ? 'Guardar Cambios' : 'Crear Doctor' }}</button>
                        </form>

                    @else 
                    <h5 class="card-title fw-semibold mb-4">Bienvenido {{Auth::user()->name}}</h5>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/selectize@0.15.2/dist/js/selectize.min.js"></script>
<script>

    @if(Auth::user()->role_id==5)
    $(document).ready(function() {
        $('#doctorForm').on('submit', function(e) {
            e.preventDefault();

            let url = '{{ $doctor ? route("doctor.update", Auth::user()->id) : route("doctor.store") }}';
            let method = '{{ $doctor ? 'PUT' : 'POST' }}';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    
                    $.notify({ message: response.success }, { type: 'success' });
                    window.location.href = "{{ route('dashboard') }}";
                },
                error: function(response) {
                    $.notify({ message: 'Ocurrió un error, por favor intenta de nuevo.' }, { type: 'danger' });
                  
                }
            });
        });
    });
    @endif
</script>
@endsection
