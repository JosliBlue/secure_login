@extends('app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>¡Bienvenido!</h4>
                    </div>
                    <div class="card-body">
                        <h5>Hola, {{ Auth::user()->getEmail() }}</h5>
                        <p>Has iniciado sesión correctamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
