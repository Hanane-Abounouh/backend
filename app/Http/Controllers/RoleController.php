<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Appliquer à toutes les méthodes
        // $this->middleware('auth:sanctum')->only('index', 'show'); // Appliquer uniquement aux méthodes 'index' et 'show'
        // $this->middleware('auth:sanctum')->except('index'); // Appliquer à toutes les méthodes sauf 'index'
    }
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($request->all());

        return response()->json($role, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $role = Role::find($id);

        if ($role) {
            return response()->json($role);
        }

        return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::find($id);

        if ($role) {
            $role->update($request->all());
            return response()->json($role);
        }

        return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully']);
        }

        return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
    }
}
