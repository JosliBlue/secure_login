@extends('app')

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
                <h1 class="text-2xl font-bold text-gray-800">Secure Login</h1>
            </div>

            <!-- Tabs -->
            <div class="flex justify-between mb-6 border-b border-gray-200">
                <button id="signInTab"
                    class="w-1/2 text-center text-gray-600 pb-2 font-medium border-b-2 border-transparent hover:border-red-800 focus:outline-none transition">Iniciar
                    sesion</button>
                <button id="signUpTab"
                    class="w-1/2 text-center text-gray-600 pb-2 font-medium border-b-2 border-transparent hover:border-red-800 focus:outline-none transition">Registrarse</button>
            </div>

            <!-- Sign In Form -->
            <form id="signInForm" class="space-y-4" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Correo electronico</label>
                    <input required type="email" name="email" value="{{ old('email') }}"
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800">
                    @if ($errors->has('email'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Contraseña</label>
                    <input required type="password" name="password" placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800">
                    @if ($errors->has('password'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                <button
                    class="w-full bg-red-900 hover:bg-red-700 text-white font-medium py-3 rounded-lg shadow-md hover:shadow-lg transition">Iniciar
                    Sesion</button>
            </form>

            <!-- Sign Up Form (Hidden by Default) -->
            <form id="signUpForm" class="space-y-4 hidden" method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Correo electrónico</label>
                    <input required type="email" name="register_email" value="{{ old('register_email') }}"
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800">
                    @if ($errors->has('register_email'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('register_email') }}</div>
                    @endif
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Contraseña</label>
                    <input required type="password" name="register_password" placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800">
                    @if ($errors->has('register_password'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('register_password') }}</div>
                    @endif
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Confirmar Contraseña</label>
                    <input required type="password" name="register_password_confirmation" placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800">
                </div>
                <button type="submit"
                    class="w-full bg-red-900 hover:bg-red-700 text-white font-medium py-3 rounded-lg shadow-md hover:shadow-lg transition">Registrarse</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const signInTab = document.getElementById("signInTab");
        const signUpTab = document.getElementById("signUpTab");
        const signInForm = document.getElementById("signInForm");
        const signUpForm = document.getElementById("signUpForm");

        // Función para activar tab de login
        function activateSignInTab() {
            signInForm.classList.remove("hidden");
            signUpForm.classList.add("hidden");
            signInTab.classList.add("border-red-800", "text-red-800");
            signUpTab.classList.remove("border-red-800", "text-red-800");
        }

        // Función para activar tab de registro
        function activateSignUpTab() {
            signUpForm.classList.remove("hidden");
            signInForm.classList.add("hidden");
            signUpTab.classList.add("border-red-800", "text-red-800");
            signInTab.classList.remove("border-red-800", "text-red-800");
        }

        // Event listeners para los tabs
        signInTab.addEventListener("click", activateSignInTab);
        signUpTab.addEventListener("click", activateSignUpTab);
    </script>
@endpush
