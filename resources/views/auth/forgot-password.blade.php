<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Anita Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap');

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #faf8f6; }

        .login-section {
            background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-rose-50 px-8 py-5 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-spa text-white text-sm"></i>
            </div>
            <span class="text-xl font-bold tracking-tighter">Anita<span class="text-rose-500">Salon</span></span>
        </div>
        <nav class="hidden lg:flex space-x-10 text-[11px] font-bold uppercase tracking-widest text-gray-400">
            <a href="#inicio" class="hover:text-rose-500 transition">Inicio</a>
            <a href="#nosotros" class="hover:text-rose-500 transition">Nosotros</a>
            <a href="#servicios" class="hover:text-rose-500 transition">Tratamientos</a>
            <a href="#contacto" class="hover:text-rose-500 transition">Contacto</a>
        </nav>
        <a href="#acceso" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest transition shadow-lg shadow-rose-100">
            Reservaciones
        </a>
    </header>

    <!-- Recovery Section -->
    <section id="acceso" class="min-h-screen pt-32 pb-20 px-6 max-w-2xl mx-auto flex flex-col items-center text-center">
        <div class="bg-white p-8 lg:p-12 rounded-3xl shadow-2xl w-full max-w-md">
            <div class="mb-6 text-center">
                <div class="w-16 h-16 bg-rose-500 rounded-2xl flex items-center justify-center mb-4 shadow-xl shadow-rose-100 mx-auto">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-semibold mb-2">Recuperar tu contraseña</h2>
                <p class="text-gray-600">
                    Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 bg-green-50 text-green-800 text-sm p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-800 text-sm p-3 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', request()->email) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>

                <button type="submit"
                        class="w-full bg-rose-500 text-white py-2 px-4 rounded-md hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-300 transition">
                    Enviar enlace de recuperación
                </button>
            </form>

            <div class="mt-6 text-sm text-gray-500">
                ¿Recuerdas tu contraseña? <a href="{{ route('login') }}" class="text-rose-600 hover:underline">Inicia sesión</a>
            </div>
        </div>
    </section>

</body>
</html>