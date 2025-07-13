@extends('app')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Gestión de Contraseñas</h2>
        <p class="mt-2 text-sm text-gray-600">Cambia tu contraseña y consulta el historial de contraseñas anteriores</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif <!-- Formulario de cambio de contraseña -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8">
        <details class="group">
            <summary
                class="px-6 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                Cambiar Contraseña
            </summary>

            <div class="px-6 py-6">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña Actual
                        </label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"
                            required>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror"
                            required>
                        @error('new_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            Debe contener al menos 8 caracteres y no haber sido usada antes.
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>
        </details>
    </div>

    <!-- Tabla de Historial de contraseñas -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        @if ($passwordHistories->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Historial de Contraseñas Anteriores</h3>
                <p class="mt-1 text-sm text-gray-600">Se mantienen las últimas contraseñas para evitar reutilización</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <caption class="sr-only">Historial de contraseñas anteriores</caption>
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contraseña
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Cambio
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($passwordHistories as $index => $history)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    #{{ $passwordHistories->count() - $index }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        ••••••••••••••••••••
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $history->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactiva
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center bg-white shadow-xl rounded-lg">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                    <iconify-icon icon="mdi:lock-outline" width="64" height="64"></iconify-icon>
                </div>
                <p class="text-sm text-gray-500">Aún no se han registrado contraseñas anteriores para tu cuenta.</p>
                <p class="text-xs text-gray-400 mt-1">Cuando cambies tu contraseña, aparecerá aquí</p>
            </div>
        @endif
    </div>
@endsection
