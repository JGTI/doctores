<?php 

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $query = Provider::with('doctor');

            // Si el usuario es Doctor, solo mostrar sus propios proveedores
            if ($user->role_id == 2) { // Asumiendo que 2 es el ID del rol de Doctor
                $query->where('doctor_id', $user->id);
            }

            $data = $query->select('providers.*');
            
            return datatables()->of($data)
                ->addColumn('doctor_name', function($row) {
                    return $row->doctor->name;
                })
                ->addColumn('status', function($row) {
                    return $row->status;
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

                    // Los doctores solo pueden editar/eliminar sus propios proveedores
                    if ($user->role_id == 1 || ($user->role_id == 2 && $row->doctor_id == $user->id)) {
                        $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editProvider">Editar</a>';
                        $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteProvider">Eliminar</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action','phone'])
                ->make(true);
        }

        // Obtener la lista de doctores solo si es Admin
        $doctors = User::where('role_id', 2)->get();

        return view('providers.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255', // Validación del campo address
            'doctor_id' => 'required|exists:users,id', // Validar el doctor
            'status' => 'required|boolean', // Validar el estado
        ]);

        // Permitir solo al Admin registrar proveedores para cualquier doctor
        // Los doctores solo pueden registrar proveedores para ellos mismos
        if ($user->role_id == 2) {
            $request->merge(['doctor_id' => $user->id]);
        }

        // Crear o actualizar el proveedor
        Provider::updateOrCreate(
            ['id' => $request->provider_id],
            [
                'doctor_id' => $request->doctor_id,
                'name' => $request->name,
                'contact_name' => $request->contact_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address, // Guardar el campo address
                'status' => $request->status,
            ]
        );

        return response()->json(['success' => 'Operación exitosa']);
    }

    public function edit($id)
    {
        $user = Auth::user();
        $provider = Provider::find($id);

        // Los doctores solo pueden editar sus propios proveedores
        if ($user->role_id == 1 || ($user->role_id == 2 && $provider->doctor_id == $user->id)) {
            return response()->json($provider);
        }

        return response()->json(['error' => 'No autorizado'], 403);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $provider = Provider::find($id);

        // Los doctores solo pueden eliminar sus propios proveedores
        if ($user->role_id == 1 || ($user->role_id == 2 && $provider->doctor_id == $user->id)) {
            $provider->delete();
            return response()->json(['success' => 'Proveedor eliminado']);
        }

        return response()->json(['error' => 'No autorizado'], 403);
    }
}
