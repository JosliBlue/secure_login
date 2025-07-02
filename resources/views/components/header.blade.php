<!-- Navbar -->
<style>
    .active {
        border-bottom: 2px solid white;
        padding-bottom: 2px;
    }
</style>
<nav class="sticky top-0 w-full z-50 nav-blur bg-red-900 border-b border-red-800/30">
    <div class="container mx-auto py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <img src="{{ asset('secure_login.png') }}" alt="SecureLogin Logo" class="w-11 h-11">
                </div>
                <span class="ml-2 text-xl font-serif font-bold text-white">SecureLogin</span>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('logs') }}"
                    class="@if (Route::currentRouteName() == 'logs') active @endif
                    text-white hover:text-red-200 font-sans font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-white hover:pb-1">Mis
                    Logs</a>
                <a href="{{ route('passwords') }}"
                    class="@if (Route::currentRouteName() == 'passwords') active @endif
                    text-white hover:text-red-200 font-sans font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-white hover:pb-1">Contraseñas</a>
                <a href="{{ route('questions') }}"
                    class="@if (Route::currentRouteName() == 'questions') active @endif
                        text-white hover:text-red-200 font-sans font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-white hover:pb-1">Preguntas</a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('logout') }}"
                        class="px-4 py-2 bg-white text-red-800 font-bold rounded-full transform hover:scale-105 transition-transform duration-200 hover:shadow-lg hover:shadow-red-900/30">Cerrar
                        Sesión</a>
                </div>
            </div>
        </div>
    </div>
</nav>
