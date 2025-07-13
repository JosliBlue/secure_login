<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpService
{
    /**
     * Genera un código OTP seguro con caracteres alfanuméricos (mayúsculas y minúsculas)
     */
    public static function generateSecureOtp($length = 8)
    {
        // Caracteres seguros para OTP con mayúsculas y minúsculas
        // (evitamos caracteres confusos como 0, O, I, l, 1)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $otp = '';

        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $otp;
    }

    /**
     * Genera y guarda un OTP para un usuario (cifrado en la BD)
     */
    public static function generateOtpForUser(User $user, $expirationMinutes = 10)
    {
        $otp = self::generateSecureOtp();
        $expiresAt = Carbon::now()->addMinutes($expirationMinutes);

        $user->update([
            'otp_code' => Crypt::encryptString($otp), // Cifrar el código OTP
            'otp_expires_at' => $expiresAt,
        ]);

        return $otp; // Retornar el código sin cifrar para enviarlo por email
    }

    /**
     * Valida si un OTP es correcto y no ha expirado
     */
    public static function validateOtp(User $user, $otpCode)
    {
        if (!$user->otp_code || !$user->otp_expires_at) {
            return false;
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            // OTP expirado, limpiar
            $user->update([
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);
            return false;
        }

        try {
            // Descifrar el código OTP almacenado y comparar
            $storedOtp = Crypt::decryptString($user->otp_code);
            return $storedOtp === $otpCode; // Comparación exacta, distinguiendo mayúsculas y minúsculas
        } catch (\Exception $e) {
            // Si no se puede descifrar, considerarlo inválido
            return false;
        }
    }

    /**
     * Limpia el OTP después de usarlo
     */
    public static function clearOtp(User $user)
    {
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);
    }

    /**
     * Obtiene el OTP descifrado para un usuario (solo para debugging - no usar en producción)
     */
    public static function getDecryptedOtp(User $user)
    {
        if (!$user->otp_code) {
            return null;
        }

        try {
            return Crypt::decryptString($user->otp_code);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Envía el OTP por correo electrónico
     */
    public static function sendOtpEmail(User $user, $otpCode)
    {
        $email = $user->getEmail();

        Mail::send('emails.otp', ['otp' => $otpCode], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Código de verificación - Secure Login');
        });
    }
}
