<?php

namespace App\Http\Controllers;

use App\Models\DoctorDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorDetailsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'specialty' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:255',
            'office_start' => 'required|string',
            'office_end' => 'required|string',
            'joining_date' => 'nullable|date',
            'additional_contact_info' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
        ]);
       
        
        $officeHours = $request->office_start . ' - ' . $request->office_end;

        DoctorDetails::create([
            'user_id' => Auth::user()->id,
            'specialty' => $request->specialty,
            'license_number' => $request->license_number,
            'office_address' => $request->office_address,
            'office_hours' => $officeHours,
            'joining_date' => $request->joining_date,
            'additional_contact_info' => $request->additional_contact_info,
            'state' => $request->state,
        ]);

        // Actualizar role_id y active en la tabla users
        $user = Auth::user();
        $user->role_id = 2;
        $user->active = 1;
        $user->save();

        return response()->json(['success' => 'Doctor creado exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        // Verificar si el usuario es un doctor y está intentando modificar su propio perfil
        if (Auth::user()->role_id == 2 && Auth::user()->id != $id) {
            return response()->json(['error' => 'No tienes permiso para modificar esta información.'], 403);
        }

        // Verificar si el usuario es un administrador (role_id = 1)
        if (Auth::user()->role_id == 1 || (Auth::user()->role_id == 2 && Auth::user()->id == $id)) {
            $request->validate([
                'specialty' => 'nullable|string|max:255',
                'license_number' => 'nullable|string|max:255',
                'office_address' => 'nullable|string|max:255',
                'office_start' => 'required|string',
                'office_end' => 'required|string',
                'joining_date' => 'nullable|date',
                'additional_contact_info' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
            ]);

            // Combinar las horas de inicio y cierre en un solo campo
            $officeHours = $request->office_start . ' - ' . $request->office_end;

            // Buscar el registro del doctor y actualizarlo
            $doctor = DoctorDetails::where('user_id', $id)->firstOrFail();
            $doctor->update([
                'specialty' => $request->specialty,
                'license_number' => $request->license_number,
                'office_address' => $request->office_address,
                'office_hours' => $officeHours,
                'joining_date' => $request->joining_date,
                'additional_contact_info' => $request->additional_contact_info,
                'state' => $request->state,
            ]);

            return response()->json(['success' => 'Información del Doctor actualizada con éxito.']);
        }

        // Si el usuario no es ni doctor del perfil ni administrador, denegar acceso
        return response()->json(['error' => 'No tienes permiso para modificar esta información.'], 403);
    }


}
