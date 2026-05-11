<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero');
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Valores por defecto
        $totalVentas = 0;
        $totalProductos = \App\Models\Producto::count();
        $totalProduccion = 0;

        // Obtener el nombre del rol
        $rolNombre = $usuario->rol ? $usuario->rol->nombre : 'Administrador';

        return view('perfil', [
            'usuario' => $usuario,
            'totalVentas' => $totalVentas,
            'totalProductos' => $totalProductos,
            'totalProduccion' => $totalProduccion,
            'rolNombre' => $rolNombre
        ]);
    }

    /**
     * Actualizar información del perfil
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('usuarios')->ignore($usuario->codigo, 'codigo'),
            ],
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'sexo' => 'nullable|in:M,F',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Actualizar campos
        $usuario->nombre = $validated['nombre'];
        $usuario->email = $validated['email'];
        $usuario->sexo = $validated['sexo'] ?? null;

        if (isset($validated['telefono'])) {
            $usuario->telefono = $validated['telefono'];
        }

        if (isset($validated['direccion'])) {
            $usuario->direccion = $validated['direccion'];
        }

        if ($request->hasFile('imagen')) {
            $this->deleteStoredImage($usuario->imagen);
            $usuario->imagen = $request->file('imagen')->store('perfiles', 'public');
        }

        $usuario->save();

        return redirect()->back()->with('success', '✅ Perfil actualizado correctamente');
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed|regex:/^(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.regex' => 'La contraseña debe incluir al menos una mayuscula y un numero.',
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        if (!Hash::check($request->current_password, $usuario->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])->withInput();
        }

        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->back()->with('success', '✅ Contraseña actualizada correctamente');
    }

    /**
     * Eliminar cuenta
     */
    public function destroy(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Debes ingresar tu contraseña para eliminar la cuenta.'
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        if (!Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['deletion_password' => 'La contraseña ingresada es incorrecta.'])->withInput();
        }

        $this->deleteStoredImage($usuario->imagen);

        // Eliminar usuario
        $usuario->delete();

        Auth::logout();

        return redirect('/login')->with('success', 'Cuenta eliminada correctamente');
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
