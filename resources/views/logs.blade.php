@extends('app')

@section('content')
    <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Mis Logs</h2>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h4 class="text-xl font-semibold mb-4">¡Bienvenido!</h4>
        <h5 class="text-lg font-medium mb-2">Hola, {{ Auth::user()->getEmail() }}</h5>
        <p class="text-gray-700">Has iniciado sesión correctamente.</p>
    </div>
@endsection
