<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - FinanceTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        }

        .input-focus:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .card-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-900">
    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-40 -right-40 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70">
        </div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class=" w-16 h-16 rounded-2xl flex items-center justify-center">
                        <img class="header-logo-img w-7 h-7 sm:w-8 sm:h-8 md:w-20 md:h-20 object-contain flex-shrink-0 "
                            src="{{ asset('img/financeHubLogo.png') }}" alt="Logo FinanceHub">
                    </div>
                </div>
                <div class="flex justify-center space-x-1">
                    <h1 class="text-3xl font-bold text-white">Finance</h1>
                    <h1 class="text-3xl font-bold text-[#ff6b00]">Hub</h1>
                </div>
                <p class="text-slate-400 mt-2">Kelola keuangan dengan mudah</p>
            </div>

            <!-- Content -->
            @yield('content')

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} FinanceTrack. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>