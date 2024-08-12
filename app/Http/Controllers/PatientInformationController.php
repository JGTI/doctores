<?php

namespace App\Http\Controllers;

use App\Models\PatientInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientInformationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $query = PatientInformation::with('doctor');

            // Si el usuario es Doctor, solo mostrar sus propios pacientes
            if ($user->role_id == 2) { // Asumiendo que 2 es el ID del rol de Doctor
                $query->where('doctor_id', $user->id);
            }

            $data = $query->select('patient_information.*');
            
            return datatables()->of($data)
                ->addColumn('doctor_name', function($row) {
                    return $row->doctor->name;
                })
                ->addColumn('phone', function($row) {
                    if ($row->phone) {
                        $phone = preg_replace('/\D+/', '', $row->phone); // Elimina todo menos números
                        return '<a href="https://wa.me/'.$phone.'" target="_blank">'.$row->phone.'</a>';
                    }
                    return 'N/A';
                })
                ->addColumn('action', function($row) use ($user) {
                    $btn = '';

                    // Los doctores solo pueden editar/eliminar sus propios pacientes
                    if ($user->role_id == 1 || ($user->role_id == 2 && $row->doctor_id == $user->id)) {
                        $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editPatient">Editar</a>';
                        $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deletePatient">Eliminar</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['phone', 'action'])
                ->make(true);
        }

        // Obtener la lista de doctores para el formulario
        $doctors = User::where('role_id', 2)->get();

        return view('patients.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de los datos
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:1',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'doctor_id' => 'required|exists:users,id',
        ]);

        // Permitir solo al Admin o al Doctor propietario del paciente crear/editar
        if ($user->role_id == 1 || ($user->role_id == 2 && $request->doctor_id == $user->id)) {
            // Crear o actualizar la información del paciente
            PatientInformation::updateOrCreate(
                ['id' => $request->patient_id],
                [
                    'doctor_id' => $request->doctor_id,
                    'patient_name' => $request->patient_name,
                    'curp' => $request->curp,
                    'rfc' => $request->rfc,
                    'address' => $request->address,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'medical_history' => $request->medical_history,
                    'allergies' => $request->allergies,
                    'current_medication' => $request->current_medication,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]
            );

            return response()->json(['success' => 'Operación exitosa']);
        }

        return response()->json(['error' => 'No autorizado'], 403);
    }

    public function edit($id)
    {
        $user = Auth::user();
        $patient = PatientInformation::find($id);

        // Permitir solo al Admin o al Doctor propietario editar
        if ($user->role_id == 1 || ($user->role_id == 2 && $patient->doctor_id == $user->id)) {
            return response()->json($patient);
        }

        return response()->json(['error' => 'No autorizado'], 403);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $patient = PatientInformation::find($id);

        // Permitir solo al Admin o al Doctor propietario eliminar
        if ($user->role_id == 1 || ($user->role_id == 2 && $patient->doctor_id == $user->id)) {
            $patient->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        }

        return response()->json(['error' => 'No autorizado'], 403);
    }
}
