<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nosecaen')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .user-dropdown { position: relative; display: inline-block; }
        .user-dropdown-content { display: none; position: absolute; right: 0; background-color: white; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 100; border: 1px solid #ddd; border-radius: 4px; }
        .user-dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; }
        .user-dropdown-content a:hover {background-color: #f1f1f1;}
        .user-dropdown:hover .user-dropdown-content {display: block;}
        .user-btn { color: white; text-decoration: none; padding: 10px; }
        .user-btn:hover { color: #ddd; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Nosecaen S.L.</a>
            <div class="navbar-nav me-auto">
                @auth
                    @if(Auth::user()->tipo === 'administrador')
                        <a class="nav-link" href="{{ route('empleados.index') }}">Empleados</a>
                        <a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a>
                        <a class="nav-link" href="{{ route('incidencias.index') }}">Incidencias</a>
                        <a class="nav-link" href="{{ route('cuotas.index') }}">Cuotas</a>
                    @else
                        <a class="nav-link" href="{{ route('incidencias.index') }}">Mis Incidencias</a>
                    @endif
                @endauth
            </div>
            @auth
            <div class="user-dropdown">
                <a href="#" class="user-btn fw-bold">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->nombre }} ▾
                </a>
                <div class="user-dropdown-content">
                    <a href="{{ route('mi-perfil') }}"><i class="fas fa-id-card"></i> Mi Perfil</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger w-100 text-start px-3 py-2" style="text-decoration:none;">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-light btn-sm">Iniciar Sesión</a>
            @endauth
        </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="text-center text-muted mt-5 py-3 border-top">
        <small>&copy; 2026 Nosecaen S.L. - CFGS DAW</small>
    </footer>

    <!-- ✅ LÍNEA AÑADIDA: Permite inyectar scripts desde las vistas -->
    @stack('scripts')
</body>
</html>