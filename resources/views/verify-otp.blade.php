@extends('app')

@section('title', 'Verificación OTP')

@section('content')
    <div class="flex items-center justify-center min-h-screen p-4 relative">

        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat filter brightness-50 contrast-125 saturate-75"
            style="background-image: url('{{ asset('fondo.jpg') }}');"></div>

        <div class="absolute inset-0 bg-black/40"></div>

        <div
            class="w-full max-w-md bg-gray-100 backdrop-blur-sm shadow-2xl rounded-2xl p-6 md:p-8 transition-all duration-300 relative z-10">
            <!-- Header with Logo -->
            <div class="text-center mb-6">
                <img src="{{ asset('secure_login.png') }}" alt="Secure Login" class="mx-auto mb-4 h-32 w-auto">
                <h1 class="text-2xl font-bold text-gray-800">Verificación de Seguridad</h1>
                <p class="text-gray-600 mt-2">Código enviado a tu correo electronico</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-red-500 text-sm">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-green-500 text-sm">{{ session('success') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- OTP Verification Form -->
            <form method="POST" action="{{ route('verify.otp') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Código de Verificación</label>
                    <input type="text" id="otp_code" name="otp_code" maxlength="8" value="{{ old('otp_code') }}"
                        placeholder="ABC123xy"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800 text-center text-xl font-mono tracking-widest"
                        required autocomplete="off" style="letter-spacing: 0.3em;">
                    <p class="mt-1 text-sm text-gray-500">Ingresa el código de 8 caracteres (mayúsculas o minúsculas)</p>
                </div>

                <button type="submit"
                    class="w-full bg-red-900 hover:bg-red-700 text-white font-medium py-3 rounded-lg shadow-md hover:shadow-lg transition">
                    Verificar Código
                </button>
            </form>

            <!-- Resend OTP Section -->
            <div class="mt-6 text-center space-y-3">
                <p class="text-sm text-gray-600">¿No recibiste el código?</p>
                <form method="POST" action="{{ route('resend.otp') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-red-800 hover:text-red-600 font-medium text-sm underline transition">
                        Reenviar código
                    </button>
                </form>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('show-login') }}" class="text-gray-600 hover:text-gray-800 text-sm transition">
                    ← Volver al inicio de sesión
                </a>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Importante</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p>Este código expira en 10 minutos por tu seguridad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Permitir solo caracteres alfanuméricos (mayúsculas y minúsculas)
        document.getElementById('otp_code').addEventListener('input', function(e) {
            // Permitir solo letras y números, mantener el caso original
            e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '');
        });

        // Auto-focus en el campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('otp_code').focus();
        });
    </script>
@endpush
