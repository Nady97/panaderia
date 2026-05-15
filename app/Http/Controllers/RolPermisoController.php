<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRolPermisoRequest;

class RolPermisoController extends Controller
{
 // withCount() agrega dos atributos automáticos: $rol->usuarios_count → Número de usuarios con ese rol
// $rol->permisos_count → Número de permisos asignados
  public function index()
  {
    $roles = Rol::withCount(['usuarios', 'permisos'])->orderBy('nombre')->get();

    return view('roles.index', compact('roles'));
  }

  public function edit(Rol $rol)
  {
    $rol->load('permisos');
    $permisos = Permiso::orderBy('modulo')->orderBy('nombre')->get();

    return view('roles.permisos', compact('rol', 'permisos'));
  }

  //  Con Form Request
  public function update(UpdateRolPermisoRequest $request, Rol $rol)
  {
      $rol->permisos()->sync($request->validated()['permisos'] ?? []);
      return redirect()->route('roles.permisos.edit', $rol)->with('success', 'Permisos actualizados.');
  }
}
