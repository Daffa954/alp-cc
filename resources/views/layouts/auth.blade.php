<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animation definitions */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Custom Scrollbar for nicer UX */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a; 
        }
        ::-webkit-scrollbar-thumb {
            background: #334155; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569; 
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen font-sans text-slate-300 selection:bg-cyan-500 selection:text-white">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4 md:p-6 lg:p-8">
        
        <div class="w-full max-w-2xl">
            @yield('content')
        </div>

        <div class="mt-8 text-slate-500 text-xs text-center">
            &copy; {{ date('Y') }} Finance AI. All rights reserved.
        </div>
    </div>

</body>
</html>