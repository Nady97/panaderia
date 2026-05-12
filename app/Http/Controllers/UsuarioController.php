<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\BitacoraAcceso;
use App\Models\BitacoraCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('rol');

        // Búsqueda por nombre, email o código
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        // Filtro por Rol
        if ($request->filled('rol_id') && $request->rol_id !== 'all') {
            $query->where('rol_id', $request->rol_id);
        }

        $usuarios = $query->paginate(10)->withQueryString();

        $roles = Rol::all();
        $totalUsuarios = Usuario::count();

        $conteoPorRolId = Usuario::select('rol_id', DB::raw('count(*) as total'))
            ->groupBy('rol_id')
            ->pluck('total', 'rol_id');

        $totalAdministradores = 0;
        $totalCajeros = 0;
        $totalPanaderos = 0;
        $totalProveedores = 0;
        $totalClientes = 0;
        $totalPorRol = [];

        foreach ($roles as $rol) {
            $cantidad = $conteoPorRolId->get($rol->id, 0);

            if ($rol->slug === 'proveedor' || $rol->nombre === 'Proveedor') {
                $cantidad = \App\Models\Proveedor::count();
            }

            $totalPorRol[$rol->slug] = $cantidad;

            switch ($rol->nombre) {
                case 'Administrador':
                    $totalAdministradores = $cantidad;
                    break;
                case 'Cajero':
                    $totalCajeros = $cantidad;
                    break;
                case 'Cocinero / Panadero':
                    $totalPanaderos = $cantidad;
                    break;
                case 'Proveedor':
                    $totalProveedores = $cantidad;
                    break;
                case 'Cliente':
                    $totalClientes = $cantidad;
                    break;
            }
        }

        return view('usuarios.index', compact(
            'usuarios',
            'totalUsuarios',
            'totalAdministradores',
            'totalCajeros',
            'totalPanaderos',
            'totalProveedores',
            'totalClientes',
            'roles',
            'totalPorRol'
        ));
    }

    public function show(string $codigo)
    {
        $usuario = Usuario::with('rol')->where('codigo',  $codigo)->firstOrFail();
        return view('usuarios.show', compact('usuario'));
    }

    public function historial(string $codigo)
    {
        $usuario = Usuario::with('rol')->where('codigo', $codigo)->firstOrFail();
        $bitacoras = BitacoraAcceso::where('usuario_codigo', $usuario->codigo)
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'accesos')
            ->withQueryString();

        $cambios = BitacoraCambio::where('usuario_codigo', $usuario->codigo)
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'cambios')
            ->withQueryString();

        return view('usuarios.historial', compact('usuario', 'bitacoras', 'cambios'));
    }

    public function historialPdf(string $codigo)
    {
        $usuario = Usuario::with('rol')->where('codigo', $codigo)->firstOrFail();
        $bitacoras = BitacoraAcceso::where('usuario_codigo', $usuario->codigo)
            ->orderBy('created_at', 'desc')
            ->get();

        $cambios = BitacoraCambio::where('usuario_codigo', $usuario->codigo)
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = app('dompdf.wrapper')->loadView('usuarios.historial-pdf', compact('usuario', 'bitacoras', 'cambios'));

        return $pdf->download('historial-' . strtolower($usuario->codigo) . '.pdf');
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:usuarios',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*\d).+$/',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'sexo' => 'nullable|in:M,F',
            'rol_id' => 'required|exists:roles,id',
        ], [
            'password.regex' => 'La contrasena debe incluir al menos una mayuscula y un numero.',
        ]);

        Usuario::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'sexo' => $request->sexo,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit(string $codigo)
    {
        $usuarioActual = auth()->user();
        if (!$usuarioActual->rol || $usuarioActual->rol->nombre !== 'Administrador') {
            return redirect()->route('usuarios.index')->with('error', 'Acceso denegado: Solo los administradores pueden editar usuarios.');
        }

        $usuario = Usuario::with('rol')->where('codigo', $codigo)->firstOrFail();
        $roles = Rol::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, string $codigo)
    {
        $usuario = Usuario::where('codigo', $codigo)->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $codigo . ',codigo',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'sexo' => 'nullable|in:M,F',
            'rol_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6|confirmed|regex:/^(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'password.regex' => 'La contrasena debe incluir al menos una mayuscula y un numero.',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'sexo' => $request->sexo,
            'rol_id' => $request->rol_id,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(string $codigo)
    {
        $usuarioActual = auth()->user();

        if (!$usuarioActual->rol || $usuarioActual->rol->nombre !== 'Administrador') {
            return redirect()->route('usuarios.index')->with('error', 'Acceso denegado: Solo los administradores pueden eliminar usuarios.');
        }

        if ($usuarioActual->codigo === $codigo) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta activamente.');
        }

        $usuario = Usuario::where('codigo', $codigo)->firstOrFail();
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function resetPassword(string $codigo)
    {
        $usuarioActual = auth()->user();

        if ($usuarioActual->codigo === $codigo) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes restablecer tu propia contrasena.');
        }

        $usuario = Usuario::where('codigo', $codigo)->firstOrFail();

        $tempPassword = Str::upper(Str::random(4)) . random_int(0, 9) . Str::lower(Str::random(5));

        $usuario->password = Hash::make($tempPassword);
        $usuario->remember_token = Str::random(60);
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', "Contrasena temporal para {$usuario->nombre}: {$tempPassword}");
    }
    /**
     * Forzar cierre de sesión de un usuario específico (Admin)
     */
    public function forceLogout($codigo)
    {
        $usuario = Usuario::findOrFail($codigo);

        // Eliminar sesiones de la tabla sessions
        DB::table('sessions')->where('user_id', $codigo)->delete();

        // Eliminar caché de actividad
        \Illuminate\Support\Facades\Cache::forget('user_last_activity_' . $codigo);

        // Actualizar fecha de última salida
        $usuario->update(['last_logout_at' => now()]);

        return back()->with('success', "Sesión de {$usuario->nombre} cerrada forzosamente.");
    }
}
