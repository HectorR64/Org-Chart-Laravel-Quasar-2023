<?php

namespace App\Http\Controllers\Modules;

use App\Http\Resources\PersonalResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Personal;
use App\Exports\PersonalExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Consulta el registro que no tiene realcion para que empiece por ese
            $rootNodes = Personal::whereNull('parent_id')->get();
            //Llama al resource para serializar el objeto a json
            return PersonalResource::collection($rootNodes);
        } catch (\Exception $e) {
            // Registra la excepción en el log de Laravel
            Log::error('Error en OrganizationController@index: ' . $e->getMessage());

            // Opcional: Puedes devolver una respuesta de error
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //valida los datos del api
            $validatedData = $request->validate([
                'label' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'expand' => 'required|boolean',
                'parent_id' => 'nullable|integer|exists:personal,id',
                'department_name' => 'required|string|exists:departments,name'
            ]);
            //Extrae el nombre del id
            $department = Department::where('name', $validatedData['department_name'])->first();

            // Verificar si el departamento existe
            if (!$department) {
                return response()->json(['error' => 'Departmento no encontrado'], 404);
            }

            // Crear un nuevo registro Personal
            $personal = new Personal;
            $personal->label = $validatedData['label'];
            $personal->expand = $validatedData['expand'];
            $personal->parent_id = $validatedData['parent_id'];
            $personal->department_id = $department->id; // Usar el ID encontrado
            $personal->save();
             // Agregar el nombre del departamento al objeto antes de devolverlo
            $personal->department = $department->name;
            // Devolver el modelo completo, incluyendo el ID
            return response()->json($personal, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            //Valida los datos del api
            $validatedData = $request->validate([
                'label' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'expand' => 'required|boolean',
                //'parent_id' => 'nullable|integer|exists:personal,id',
                'department_name' => 'required|string|exists:departments,name'
            ]);

            $personal = Personal::findOrFail($id);

            // Buscar el departamento por nombre
            $department = Department::where('name', $validatedData['department_name'])->first();

            if (!$department) {
                return response()->json(['error' => 'Departmento no encontrado'], 404);
            }

            // Actualizar el registro Personal
            $personal->label = $validatedData['label'];
            $personal->expand = $validatedData['expand'];
            //$personal->parent_id = $validatedData['parent_id'];
            $personal->department_id = $department->id; // Actualizar con el ID del departamento encontrado
            $personal->save();

            // Agregar el nombre del departamento al objeto antes de devolverlo
            $personal->department = $department->name;

            return response()->json($personal, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            //Elimina el dato que encuentra
            $personal = Personal::findOrFail($id);

            $personal->delete();

            return response()->json(['success' => 'Personal eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function excel()
    {
        try {
            // Incluir el registro padre en la consulta
            $personal = Personal::with('department', 'children','parent')->get();
            return Excel::download(new PersonalExport($personal), 'personal.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
