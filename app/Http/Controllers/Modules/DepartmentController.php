<?php

namespace App\Http\Controllers\Modules;

use App\Http\Resources\DepartmentResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $departments = Department::all()->pluck('name');
            return DepartmentResource::collection($departments);
        } catch (\Exception $e) {
            // Registra la excepción en el log de Laravel
            Log::error('Error en DepartmentController@index: ' . $e->getMessage());
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
