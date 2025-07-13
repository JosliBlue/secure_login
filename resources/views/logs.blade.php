@extends('app')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Mis Logs de Autenticación
        </h2>
        <p class="mt-2 text-sm text-gray-600">Historial de tus intentos de acceso y verificaciones de seguridad</p>
    </div>

    <!-- Tabla de Logs -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        @if ($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <caption class="sr-only">Historial de intentos de autenticación</caption>
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Detalles
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log['formatted_date'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if ($log['auth_type_raw'] === 'login') bg-gray-100 text-gray-800
                                                @elseif ($log['auth_type_raw'] === 'otp') bg-blue-100 text-blue-800
                                                @elseif ($log['auth_type_raw'] === 'question') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ $log['auth_type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if ($log['success']) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $log['success_label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        @if (data_get($log, 'details.type') === 'login')
                                            <strong>Email:</strong> {{ data_get($log, 'details.email_attempted', 'N/A') }}
                                        @elseif (data_get($log, 'details.type') === 'otp')
                                            <strong>Código ingresado:</strong>
                                            {{ data_get($log, 'details.provided_code', 'N/A') }}
                                        @elseif (data_get($log, 'details.type') === 'question')
                                            <strong>Pregunta:</strong>
                                            {{ \Illuminate\Support\Str::limit(data_get($log, 'details.question', 'N/A'), 80) }}
                                        @else
                                            <span>Sin detalles disponibles</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log['ip_address'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center bg-white shadow-xl rounded-lg">
                <p class="text-sm text-gray-500">Aún no se han registrado intentos de acceso para tu cuenta.</p>
            </div>
        @endif
    </div>
@endsection
