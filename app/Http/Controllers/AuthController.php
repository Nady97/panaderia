<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

use App\Models\Usuario;

class AuthController extends Controller
{
  public function showLoginForm()
  {
    return view('login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
      $seconds = RateLimiter::availableIn($throttleKey);
      $minutes = max(1, (int) ceil($seconds / 60));

      return back()
        ->withInput($request->only('email', 'remember'))
        ->withErrors(['email' => "Demasiados intentos fallidos. Intenta nuevamente en {$minutes} minuto(s)."]);
    }

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
      $request->session()->regenerate();
      RateLimiter::clear($throttleKey);
      return redirect()->intended('/dashboard');
    }

    RateLimiter::hit($throttleKey, 900);

    if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
      $seconds = RateLimiter::availableIn($throttleKey);
      $minutes = max(1, (int) ceil($seconds / 60));

      return back()
        ->withInput($request->only('email', 'remember'))
        ->withErrors(['email' => "Tu acceso fue bloqueado temporalmente por {$minutes} minuto(s) después de 3 intentos fallidos."]);
    }

    return back()
      ->withInput($request->only('email', 'remember'))
      ->withErrors(['email' => 'Credenciales incorrectas']);
  }

  public function showForgotPasswordForm()
  {
    return view('auth.forgot-password');
  }

  public function sendResetLinkEmail(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
      $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
      ? back()->with('status', $this->passwordStatusMessage($status))
      : back()->withInput($request->only('email'))->withErrors(['email' => $this->passwordStatusMessage($status)]);
  }

  public function showResetPasswordForm(Request $request, string $token)
  {
    return view('auth.reset-password', [
      'request' => $request,
      'token' => $token,
    ]);
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'token' => ['required'],
      'email' => ['required', 'email'],
      'password' => ['required', 'confirmed', 'min:6'],
    ]);

    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function (Usuario $user, string $password) {
        $user->forceFill([
          'password' => Hash::make($password),
          'remember_token' => Str::random(60),
        ])->save();
      }
    );

    return $status === Password::PASSWORD_RESET
      ? redirect()->route('login')->with('status', $this->passwordStatusMessage($status))
      : back()->withInput($request->only('email'))->withErrors(['email' => $this->passwordStatusMessage($status)]);
  }

  private function passwordStatusMessage(string $status): string
  {
    return match ($status) {
      Password::RESET_LINK_SENT => 'Te enviamos un enlace para recuperar tu contraseña.',
      Password::PASSWORD_RESET => 'Tu contraseña fue restablecida correctamente. Ya puedes iniciar sesión.',
      Password::INVALID_USER => 'No encontramos una cuenta registrada con ese correo electrónico.',
      Password::INVALID_TOKEN => 'El enlace de recuperación no es válido o ya expiró.',
      Password::RESET_THROTTLED => 'Debes esperar un momento antes de solicitar otro enlace de recuperación.',
      default => 'No se pudo completar la solicitud. Intenta nuevamente.',
    };
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }
}
