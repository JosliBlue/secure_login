<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ],
            [
                'email.required' => 'El campo de correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'password.required' => 'El campo de contraseña es obligatorio.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::compareEmail($request->email);
        if (!$user) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'El correo no existe.'])
                ->withInput();
        }

        if (!$user->comparePassword($request->password)) {
            return redirect()
                ->back()
                ->withErrors(['password' => 'La contraseña es incorrecta.'])
                ->withInput();
        }

        Auth::login($user);
        return redirect()
            ->route('logs')
            ->with('success', 'Inicio de sesión exitoso.');
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'register_email' => 'required|email|max:255',
                'register_password' => 'required|min:6|confirmed',
            ],
            [
                'register_email.required' => 'El campo de correo electrónico es obligatorio.',
                'register_email.email' => 'El formato del correo electrónico no es válido.',
                'register_email.max' => 'El correo no puede tener más de 255 caracteres.',
                'register_password.required' => 'El campo de contraseña es obligatorio.',
                'register_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'register_password.confirmed' => 'Las contraseñas no coinciden.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si el email ya existe (encriptado)
        $emailEncrypted = Crypt::encryptString($request->register_email);

        $existingUser = User::compareEmail($request->register_email);
        if ($existingUser) {
            return redirect()
                ->back()
                ->withErrors(['register_email' => 'Este correo ya está registrado.'])
                ->withInput();
        }

        // Crear el usuario con datos encriptados
        $passwordEncrypted = Crypt::encryptString($request->register_password);

        $user = User::create([
            'email' => $emailEncrypted,
            'password' => $passwordEncrypted,
        ]);

        // Loguear automáticamente al usuario
        Auth::login($user);

        return redirect()
            ->route('logs')
            ->with('success', 'Registro exitoso. Bienvenido!');
    }
    public function logout()
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión exitosamente.');
    }
}
