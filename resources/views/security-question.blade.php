@extends('app')

@section('title', 'Pregunta de Seguridad')

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
                <h1 class="text-2xl font-bold text-gray-800">Pregunta de Seguridad</h1>
                <p class="text-gray-600 mt-2">Último paso de verificación</p>
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

            <!-- Question Display -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <p class="text-orange-600">Ingresa la respuesta a la pregunta que te llegara a tu correo electronico</p>
            </div>

            <!-- Security Question Form -->
            <form method="POST" action="{{ route('verify.security.question') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="question_id" value="{{ $questionId }}">

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Tu Respuesta</label>
                    <input type="text" id="security_answer" name="security_answer" value="{{ old('security_answer') }}"
                        placeholder="Escribe tu respuesta aquí..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800"
                        required autocomplete="off">

                </div>

                <button type="submit"
                    class="w-full bg-red-900 hover:bg-red-700 text-white font-medium py-3 rounded-lg shadow-md hover:shadow-lg transition">
                    Verificar Respuesta
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('show-login') }}" class="text-gray-600 hover:text-gray-800 text-sm transition">
                    ← Volver al inicio de sesión
                </a>
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-focus en el campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('security_answer').focus();
        });
    </script>
@endpush
