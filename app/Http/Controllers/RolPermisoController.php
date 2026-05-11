<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolPermisoController extends Controller
{
  public function index()
  {
    $roles = Rol::orderBy('nombre')->get();

    return view('roles.index', compact('roles'));
  }

  public function edit(Rol $rol)
  {
    $rol->load('permisos');
    $permisos = Permiso::orderBy('modulo')->orderBy('nombre')->get();

    return view('roles.permisos', compact('rol', 'permisos'));
  }

  public function update(Request $request, Rol $rol)
  {
    $data = $request->validate([
      'permisos' => 'array',
      'permisos.*' => 'integer|exists:permisos,id',
    ]);

    $rol->permisos()->sync($data['permisos'] ?? []);

    return redirect()
      ->route('roles.permisos.edit', $rol)
      ->with('success', 'Permisos actualizados correctamente.');
  }
}
