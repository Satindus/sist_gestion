<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermisoRolModulo;

class PermisoRolModuloController extends Controller
{
    // Listar todos los registros de la tabla pivote
    public function index()
    {
        $data = PermisoRolModulo::all();
        return response()->json($data);
    }

    // Crear un nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'modulo_id' => 'required|exists:modulos,id',
            'permiso_id' => 'required|exists:permisos,id',
        ]);

        // Verificar si ya existe el registro
        $exists = PermisoRolModulo::where('rol_id', $request->rol_id)
                   ->where('modulo_id', $request->modulo_id)
                   ->where('permiso_id', $request->permiso_id)
                   ->first();
        if ($exists) {
            return response()->json(['message' => 'El registro ya existe'], 409);
        }

        $registro = PermisoRolModulo::create($request->all());
        return response()->json($registro, 201);
    }

    // Mostrar un registro específico (usando las 3 claves compuestas)
    public function show($rol_id, $modulo_id, $permiso_id)
    {
        $registro = PermisoRolModulo::where('rol_id', $rol_id)
                    ->where('modulo_id', $modulo_id)
                    ->where('permiso_id', $permiso_id)
                    ->first();

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        return response()->json($registro);
    }

    // Actualizar un registro existente
    public function update(Request $request, $rol_id, $modulo_id, $permiso_id)
    {
        $registro = PermisoRolModulo::where('rol_id', $rol_id)
                    ->where('modulo_id', $modulo_id)
                    ->where('permiso_id', $permiso_id)
                    ->first();

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'modulo_id' => 'required|exists:modulos,id',
            'permiso_id' => 'required|exists:permisos,id',
        ]);

        // Dado que las claves conforman la PK, si se modifican se recomienda eliminar el registro antiguo y crear uno nuevo.
        // Aquí se actualiza manualmente:
        $registro->rol_id = $request->rol_id;
        $registro->modulo_id = $request->modulo_id;
        $registro->permiso_id = $request->permiso_id;
        $registro->save();

        return response()->json($registro);
    }

    // Eliminar un registro
    public function destroy($rol_id, $modulo_id, $permiso_id)
    {
        $registro = PermisoRolModulo::where('rol_id', $rol_id)
                    ->where('modulo_id', $modulo_id)
                    ->where('permiso_id', $permiso_id)
                    ->first();

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $registro->delete();
        return response()->json(['message' => 'Registro eliminado correctamente']);
    }
}
