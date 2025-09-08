<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
    
    <title>Welcome - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }
        .card {
            background-color: #1e1e1e;
            border: 1px solid #333;
        }
        .text-primary {
            color: #64b5f6 !important;
        }
        a.btn-primary {
            background-color: #64b5f6;
            border-color: #64b5f6;
        }
        a.btn-primary:hover {
            background-color: #42a5f5;
            border-color: #42a5f5;
        }
        a.btn-outline-secondary {
            color: #e0e0e0;
            border-color: #757575;
        }
        a.btn-outline-secondary:hover {
            background-color: #757575;
            color: #121212;
        }
    </style>

</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="card text-center p-4 shadow rounded" style="max-width: 600px;">

    <h2 class="text-white font-weight-bold">
        Sistema de comunicacion para API's E-Commerce
    </h2>

    <h4 class="text-seconda mt-3">
      Prueba Tecnica desarrollada por <strong class="text-primary">Vladimir Faundez H.</strong> para la empresa <strong class="text-primary">Amplifica I/O</strong>.
    </h4>

    @auth
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary mt-5">
            Ir a Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary mt-5">
            Inicia Sesión
        </a>
    @endAuth
    

    <footer class="mt-4 text-muted small">
      <p class="mb-1">Created by Vladimir Faundez H.</p>
      <p>&copy; {{ date('Y') }} VFH DEV. All rights reserved.</p>
    </footer>

  </div>
</body>
</html>
