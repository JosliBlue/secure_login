<?php

namespace App\Services;

use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class SecurityQuestionService
{
    /**
     * Selecciona una pregunta aleatoria del usuario
     */
    public static function getRandomQuestionForUser(User $user)
    {
        $questions = $user->questions;

        if ($questions->isEmpty()) {
            return null;
        }

        return $questions->random();
    }

    /**
     * Envía una pregunta de seguridad por correo electrónico
     */
    public static function sendSecurityQuestionEmail(User $user, Question $question)
    {
        $email = $user->getEmail();
        $questionText = Crypt::decryptString($question->question_text);

        Mail::send('emails.security-question', [
            'question' => $questionText,
            'questionId' => $question->id
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Pregunta de Seguridad - Secure Login');
        });
    }

    /**
     * Valida la respuesta de una pregunta de seguridad
     */
    public static function validateSecurityAnswer(Question $question, $userAnswer)
    {
        if (!$question || !$userAnswer) {
            return false;
        }

        try {
            $correctAnswer = Crypt::decryptString($question->answer_text);

            // Comparación exacta distinguiendo mayúsculas y minúsculas, pero sin espacios adicionales
            return trim($correctAnswer) === trim($userAnswer);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene la pregunta desencriptada
     */
    public static function getDecryptedQuestion(Question $question)
    {
        try {
            return Crypt::decryptString($question->question_text);
        } catch (\Exception $e) {
            return null;
        }
    }
}
