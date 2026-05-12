<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Usuario;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $usuario = Auth::user();
            $lastActivityKey = 'user_last_activity_' . $usuario->codigo;
            $lastActivity = Cache::get($lastActivityKey);
            $timeout = config('session.lifetime') * 60; // en segundos
            
            // Si ha excedido el tiempo de inactividad
            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                // Actualizar el campo last_logout_at al expirar
                Usuario::where('codigo', $usuario->codigo)
                      ->update(['last_logout_at' => now()]);
                
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                Cache::forget($lastActivityKey);
                
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado por inactividad.');
            }
            
            // Actualizar la actividad del usuario
            Cache::put($lastActivityKey, time(), now()->addMinutes(config('session.lifetime')));
        }

        return $next($request);
    }
}