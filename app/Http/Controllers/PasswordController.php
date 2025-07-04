<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        $user = Auth::user();
        $passwordHistories = $user->passwords()->orderBy('created_at', 'desc')->limit(10)->get();

        return view('passwords', compact('passwordHistories'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!$user->comparePassword($request->current_password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.'
            ]);
        }

        // Verificar que la nueva contraseña no haya sido usada antes
        if ($user->hasUsedPassword($request->new_password)) {
            return back()->withErrors([
                'new_password' => 'No puedes usar una contraseña que ya has utilizado anteriormente.'
            ]);
        }

        // Guardar la contraseña actual en el historial antes de cambiarla
        $user->addPasswordToHistory();

        // Actualizar la contraseña
        $user->update([
            'password' => Crypt::encryptString($request->new_password)
        ]);

        return redirect()->route('passwords')
            ->with('success', 'Contraseña cambiada exitosamente.');
    }
}
