<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpService
{
    /**
     * Genera un código OTP seguro con caracteres alfanuméricos
     */
    public static function generateSecureOtp($length = 8)
    {
        // Caracteres seguros para OTP (evitamos caracteres confusos como 0, O, I, l)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $otp = '';

        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $otp;
    }

    /**
     * Genera y guarda un OTP para un usuario
     */
    public static function generateOtpForUser(User $user, $expirationMinutes = 10)
    {
        $otp = self::generateSecureOtp();
        $expiresAt = Carbon::now()->addMinutes($expirationMinutes);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => $expiresAt,
        ]);

        return $otp;
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

        return $user->otp_code === strtoupper($otpCode);
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
