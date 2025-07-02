<?php

namespace App\Services;

use App\Models\Log;
use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class AuthLogService
{
    const TYPE_LOGIN = 'login';
    const TYPE_OTP = 'otp';
    const TYPE_QUESTION = 'question';

    /**
     * Registra un intento de login (email/contraseña)
     */
    public static function logLoginAttempt(User $user, bool $success = false, Request $request, string $email)
    {
        return Log::create([
            'user_id' => $user->id,
            'auth_type' => Crypt::encryptString(self::TYPE_LOGIN),
            'success' => $success,
            'ip_address' => $request ? Crypt::encryptString($request->ip()) : null,
            'provided_answer' => $email ? Crypt::encryptString("Email: $email") : null,
        ]);
    }

    /**
     * Registra un intento de verificación OTP
     */
    public static function logOtpAttempt(User $user, string $providedOtp, bool $success = false, Request $request)
    {
        return Log::create([
            'user_id' => $user->id,
            'auth_type' => Crypt::encryptString(self::TYPE_OTP),
            'provided_otp_code' => Crypt::encryptString($providedOtp),
            'success' => $success,
            'ip_address' => Crypt::encryptString($request->ip()),
        ]);
    }

    /**
     * Registra un intento de respuesta a pregunta de seguridad
     */
    public static function logQuestionAttempt(User $user, Question $question, string $providedAnswer, bool $success = false, Request $request)
    {
        return Log::create([
            'user_id' => $user->id,
            'auth_type' => Crypt::encryptString(self::TYPE_QUESTION),
            'question_id' => $question->id,
            'provided_answer' => Crypt::encryptString($providedAnswer),
            'success' => $success,
            'ip_address' => Crypt::encryptString($request->ip()),
        ]);
    }

    /**
     * Obtiene logs de un usuario específico para mostrar en la interfaz
     */
    public static function getUserFormattedLogs($userId, $limit = 50)
    {
        return Log::with(['user', 'question'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($log) {
                // Descifrar auth_type
                $authType = null;
                try {
                    $authType = $log->auth_type ? Crypt::decryptString($log->auth_type) : null;
                } catch (\Exception $e) {
                    $authType = 'unknown';
                }

                // Descifrar ip_address
                $ipAddress = 'No disponible';
                try {
                    $ipAddress = $log->ip_address ? Crypt::decryptString($log->ip_address) : 'No disponible';
                } catch (\Exception $e) {
                    $ipAddress = 'Error al descifrar';
                }

                return [
                    'id' => $log->id,
                    'user_email' => $log->user ? $log->user->getEmail() : 'Usuario eliminado',
                    'auth_type' => $authType,
                    'auth_type_raw' => $authType,
                    'success' => $log->success,
                    'success_label' => $log->success ? 'Exitoso' : 'Fallido',
                    'details' => self::getLogDetails($log),
                    'ip_address' => $ipAddress,
                    'created_at' => $log->created_at,
                    'formatted_date' => $log->created_at->format('d/m/Y H:i:s'),
                ];
            });
    }

    /**
     * Obtiene los detalles específicos del log según el tipo
     */
    private static function getLogDetails(Log $log): array
    {
        // Descifrar auth_type
        $authType = null;
        try {
            $authType = $log->auth_type ? Crypt::decryptString($log->auth_type) : null;
        } catch (\Exception $e) {
            $authType = 'unknown';
        }

        switch ($authType) {
            case self::TYPE_LOGIN:
                // Descifrar provided_answer para login
                $emailAttempted = 'No disponible';
                try {
                    $emailAttempted = $log->provided_answer ? Crypt::decryptString($log->provided_answer) : 'No disponible';
                } catch (\Exception $e) {
                    $emailAttempted = 'Error al descifrar';
                }

                return [
                    'type' => 'login',
                    'email_attempted' => $emailAttempted,
                ];

            case self::TYPE_OTP:
                // Descifrar provided_otp_code
                $providedCode = 'No disponible';
                try {
                    $providedCode = $log->provided_otp_code ? Crypt::decryptString($log->provided_otp_code) : 'No disponible';
                } catch (\Exception $e) {
                    $providedCode = 'Error al descifrar';
                }

                return [
                    'type' => 'otp',
                    'provided_code' => $providedCode,
                    'real_code' => $log->success ? '[Correcto]' : '[Incorrecto]',
                ];

            case self::TYPE_QUESTION:
                // Descifrar provided_answer para pregunta
                $providedAnswer = 'No disponible';
                try {
                    $providedAnswer = $log->provided_answer ? Crypt::decryptString($log->provided_answer) : 'No disponible';
                } catch (\Exception $e) {
                    $providedAnswer = 'Error al descifrar';
                }

                $question = $log->question;
                $questionText = 'Pregunta eliminada';

                if ($question) {
                    try {
                        $questionText = Crypt::decryptString($question->question_text);
                    } catch (\Exception $e) {
                        $questionText = 'Error al descifrar pregunta';
                    }
                }

                return [
                    'type' => 'question',
                    'question' => $questionText,
                    'provided_answer' => $providedAnswer,
                    'answer_status' => $log->success ? 'Respuesta correcta' : 'Respuesta incorrecta',
                ];

            default:
                return ['type' => 'unknown'];
        }
    }
}
