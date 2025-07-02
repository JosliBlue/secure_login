<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use App\Services\OtpService;
use App\Services\SecurityQuestionService;
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

        // Generar y enviar OTP en lugar de iniciar sesión directamente
        try {
            $otpCode = OtpService::generateOtpForUser($user);
            OtpService::sendOtpEmail($user, $otpCode);

            return redirect()
                ->route('verify.otp.form', ['email' => $request->email])
                ->with('success', 'Hemos enviado un código de verificación a tu correo electrónico.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Error al enviar el código de verificación. Inténtalo de nuevo.'])
                ->withInput();
        }
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return redirect()->route('login');
        }

        return view('verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'otp_code' => 'required|string|size:8',
            ],
            [
                'email.required' => 'Email es requerido.',
                'otp_code.required' => 'El código OTP es obligatorio.',
                'otp_code.size' => 'El código OTP debe tener exactamente 8 caracteres.',
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
                ->route('login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        if (!OtpService::validateOtp($user, $request->otp_code)) {
            return redirect()
                ->back()
                ->withErrors(['otp_code' => 'Código incorrecto o expirado.'])
                ->withInput();
        }

        // OTP válido, limpiar y proceder con pregunta de seguridad
        OtpService::clearOtp($user);

        // Obtener una pregunta aleatoria del usuario
        $question = SecurityQuestionService::getRandomQuestionForUser($user);

        if (!$question) {
            // Si no tiene preguntas, iniciar sesión directamente
            Auth::login($user);
            return redirect()
                ->route('logs')
                ->with('success', 'Inicio de sesión exitoso.');
        }

        try {
            // Enviar pregunta por correo
            SecurityQuestionService::sendSecurityQuestionEmail($user, $question);

            // Redirigir a la página de pregunta de seguridad
            return redirect()
                ->route('security.question.form', [
                    'email' => $request->email,
                    'question_id' => $question->id
                ])
                ->with('success', 'Hemos enviado una pregunta de seguridad a tu correo.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Error al enviar la pregunta de seguridad.']);
        }
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'Email es requerido.',
                'email.email' => 'Formato de email inválido.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $user = User::compareEmail($request->email);
        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        try {
            $otpCode = OtpService::generateOtpForUser($user);
            OtpService::sendOtpEmail($user, $otpCode);

            return redirect()
                ->back()
                ->with('success', 'Nuevo código enviado a tu correo electrónico.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Error al reenviar el código. Inténtalo de nuevo.']);
        }
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

    public function showSecurityQuestionForm(Request $request)
    {
        $email = $request->get('email');
        $questionId = $request->get('question_id');

        if (!$email || !$questionId) {
            return redirect()->route('show-login');
        }

        $user = User::compareEmail($email);
        if (!$user) {
            return redirect()->route('show-login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $question = Question::find($questionId);
        if (!$question || $question->user_id !== $user->id) {
            return redirect()->route('show-login')
                ->withErrors(['error' => 'Pregunta no válida.']);
        }

        $questionText = SecurityQuestionService::getDecryptedQuestion($question);
        if (!$questionText) {
            return redirect()->route('show-login')
                ->withErrors(['error' => 'Error al procesar la pregunta.']);
        }

        return view('security-question', [
            'email' => $email,
            'question' => $questionText,
            'questionId' => $questionId
        ]);
    }

    public function verifySecurityQuestion(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'question_id' => 'required|integer',
                'security_answer' => 'required|string|min:1',
            ],
            [
                'email.required' => 'Email es requerido.',
                'question_id.required' => 'ID de pregunta es requerido.',
                'security_answer.required' => 'La respuesta es obligatoria.',
                'security_answer.min' => 'La respuesta debe tener al menos 1 carácter.',
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
                ->route('show-login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $question = Question::find($request->question_id);
        if (!$question || $question->user_id !== $user->id) {
            return redirect()
                ->route('show-login')
                ->withErrors(['error' => 'Pregunta no válida.']);
        }

        if (!SecurityQuestionService::validateSecurityAnswer($question, $request->security_answer)) {
            return redirect()
                ->back()
                ->withErrors(['security_answer' => 'Respuesta incorrecta.'])
                ->withInput();
        }

        // Respuesta correcta, iniciar sesión
        Auth::login($user);

        return redirect()
            ->route('logs')
            ->with('success', 'Inicio de sesión completado exitosamente.');
    }
}
