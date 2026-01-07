<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Finance Hub') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* --- CUSTOM CSS --- */
        
        /* Hero content alignment */
        .hero-content {
            max-width: 450px;
        }

        /* Feature boxes: Dark Teal Background */
        .feature-box {
            background-color: #0b2b2b;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }

        /* Theme Color: Orange */
        .theme-orange {
            color: #ea6201;
        }

        /* Scroll handling */
        body {
            overflow-y: auto !important;
            min-height: 100vh;
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Prevent layout breaking on zoom */
        html {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Brand Text alignment */
        .brand-text-container {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .brand-text {
            display: inline-block;
        }

        /* Header Navigation alignment */
        #main-header nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap !important;
            white-space: nowrap;
        }

        .header-logo-section {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            min-width: 0;
        }

        .header-nav-buttons {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .nav-button {
            font-size: clamp(0.75rem, 2vw, 1rem);
            padding: 0.375rem 0.75rem;
        }

        .no-wrap-buttons {
            display: flex;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        /* Responsive Text Adjustments */
        @media (max-width: 640px) {
            .hero-content {
                max-width: 100%;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .text-4xl { font-size: 1.75rem; line-height: 2.25rem; }
            .text-5xl { font-size: 2rem; line-height: 2.5rem; }
        }

        @media (max-width: 480px) {
            .header-logo-img { width: 24px !important; height: 24px !important; }
            .brand-text-white, .brand-text-orange { font-size: 0.875rem !important; }
        }
    </style>
</head>

<body class="bg-cover bg-center bg-no-repeat min-h-screen relative"
    style="background-image: url('{{ asset('img/landing0.jpg') }}'); background-attachment: fixed;">
    
    <div class="absolute inset-0 bg-black/70 z-0"></div>

    <header id="main-header"
        class="fixed top-0 left-0 w-full py-2 px-3 sm:py-3 sm:px-4 md:px-6 lg:px-8 xl:px-12 z-50 text-white transition-colors duration-300 bg-transparent">
        <nav class="flex items-center justify-between w-full max-w-7xl mx-auto flex-nowrap">
            <div class="header-logo-section">
                <img class="header-logo-img w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 object-contain flex-shrink-0 mr-2"
                    src="{{ asset('img/financeHubLogo.png') }}" alt="Logo FinanceHub">
                <div class="brand-text-container">
                    <span class="brand-text-white text-white text-sm sm:text-base md:text-lg font-bold flex-shrink-0">
                        Finance
                    </span>
                    <span class="brand-text-orange text-[#ff6b00] text-sm sm:text-base md:text-lg font-bold flex-shrink-0 ml-1">
                        Hub
                    </span>
                </div>
            </div>

            @if (Route::has('login'))
                <div class="header-nav-buttons no-wrap-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="nav-button inline-block border border-gray-300 hover:bg-[#ea6201] hover:text-white hover:border-[#ea6201] rounded-lg transition-colors duration-200 whitespace-nowrap ml-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="nav-button inline-block border border-[#ea6201] text-[#ea6201] hover:bg-[#ea6201] hover:text-white rounded-lg transition-colors duration-200 whitespace-nowrap ml-2">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="nav-button inline-block border border-[#ea6201] text-[#ea6201] hover:bg-[#ea6201] hover:text-white rounded-lg transition-colors duration-200 whitespace-nowrap ml-2">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>
    </header>

    <div class="h-16 sm:h-20 md:h-24 lg:h-28 w-full block relative z-0"></div>

    <main class="relative z-10 overflow-hidden">
        
        <section class="py-20 px-10 md:px-20 lg:px-40 pb-40 md:pb-80">
            <div class="text-white mt-5 hero-content">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                    Solusi Keuangan Cerdas untuk
                    <span class="text-[#ff6b00] block mt-1">Gaya Hidup Modern</span>
                </h1>
                <br>
                <div class="w-125 h-1 bg-[#ff6b00] mb-10 rounded"></div>

                <p class="text-lg mb-8 text-gray-300">
                    Lacak pendapatan dan pengeluaran untuk mengelola keuangan dan mengembangkan kekayaan Anda.
                </p>
            </div>
        </section>

        <section class="bg-white py-20 px-10 md:px-20 lg:px-40">
            <div class="max-w-6xl mx-auto">

                <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 theme-orange">
                    Tentang Produk Kami
                </h2>

                <p class="text-center text-gray-600 max-w-3xl mx-auto mb-16">
                    Finance Hub membantu anda mengelola keuangan dengan lebih mudah dan teratur.
                    Dengan analisis cerdas, pelacakan pengeluaran, dan fitur perencanaan keuangan yang efisien.
                </p>

                <h3 class="text-2xl font-semibold text-center text-gray-800 mb-10">
                    Fitur Utama
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.778 8.222c-4.296-4.296-11.264-4.296-15.56 0a1 1 0 000 1.414c4.296 4.296 11.264 4.296 15.56 0a1 1 0 000-1.414zM4.331 15.65a8 8 0 0111.338 0l-.337.337a7 7 0 00-10.664 0l-.337-.337zM6.992 13.011a4 4 0 016.016 0l-.337.337a3 3 0 00-5.342 0l-.337-.337z" clip-rule="evenodd"></path></svg>
                            </span>
                            <p class="text-xl font-bold">Melacak Finansial</p>
                        </div>
                        <p class="text-gray-200">Pantau pemasukan, pengeluaran, dan arus kas secara real-time.</p>
                    </div>
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 00-8 8v5a1 1 0 001 1h14a1 1 0 001-1v-5a8 8 0 00-8-8zm-5 8a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                            </span>
                            <p class="text-xl font-bold">Rekomendasi Pengeluaran</p>
                        </div>
                        <p class="text-gray-200">Saran pengeluaran bijak berdasarkan kebiasaan Anda.</p>
                    </div>
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M17.657 16.657L13.414 12.414A5 5 0 0014.243 11a5 5 0 10-7.07 0 5 5 0 00.829 1.414L3.343 16.657a1 1 0 001.414 1.414L8 13.414V17a1 1 0 102 0v-3.586l3.243 3.243a1 1 0 001.414-1.414zM10 8a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </span>
                            <p class="text-xl font-bold">Perencanaan Finansial</p>
                        </div>
                        <p class="text-gray-200">Susun rencana jangka pendek dan panjang dengan mudah.</p>
                    </div>
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.393 2.029a1 1 0 01.765.347l6.5 6.5a1 1 0 01-1.414 1.414L12 4.414V15a1 1 0 11-2 0V4.414L3.75 10.39a1 1 0 11-1.414-1.414l6.5-6.5a1 1 0 011.057-.279z" clip-rule="evenodd"></path></svg>
                            </span>
                            <p class="text-xl font-bold">Cepat & Praktis</p>
                        </div>
                        <p class="text-gray-200">Satu platform untuk semua kebutuhan finansial Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-50 py-20 px-10 md:px-20 lg:px-40">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">
                        Dipercaya oleh Ribuan Pengguna
                    </h2>
                    <div class="w-20 h-1 bg-[#ea6201] mx-auto rounded"></div>
                    <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                        Lihat bagaimana Finance Hub membantu mereka mencapai kebebasan finansial.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-[#ea6201] hover:-translate-y-2 transition-transform duration-300">
                        <div class="flex text-[#ea6201] mb-4 text-xl">★★★★★</div>
                        <p class="text-gray-600 italic mb-6">
                            "Sejak menggunakan Finance Hub, saya akhirnya bisa melacak kemana perginya gaji saya setiap bulan. Fitur rekomendasinya sangat membantu!"
                        </p>
                        <div class="flex items-center">
                            <img class="w-10 h-10 rounded-full mr-4 bg-gray-200" src="https://ui-avatars.com/api/?name=Andi+Pratama&background=ea6201&color=fff" alt="User">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Andi Pratama</h4>
                                <span class="text-xs text-gray-500">Freelancer</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-[#ea6201] hover:-translate-y-2 transition-transform duration-300">
                        <div class="flex text-[#ea6201] mb-4 text-xl">★★★★★</div>
                        <p class="text-gray-600 italic mb-6">
                            "Tampilan dashboardnya sangat bersih dan mudah dimengerti bahkan untuk orang yang awam masalah finansial seperti saya."
                        </p>
                        <div class="flex items-center">
                            <img class="w-10 h-10 rounded-full mr-4 bg-gray-200" src="https://ui-avatars.com/api/?name=Siti+Rahma&background=0b2b2b&color=fff" alt="User">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Siti Rahma</h4>
                                <span class="text-xs text-gray-500">Ibu Rumah Tangga</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-[#ea6201] hover:-translate-y-2 transition-transform duration-300">
                        <div class="flex text-[#ea6201] mb-4 text-xl">★★★★★</div>
                        <p class="text-gray-600 italic mb-6">
                            "Fitur budgeting-nya adalah penyelamat. Sekarang saya bisa menabung untuk dana darurat secara konsisten tiap bulannya."
                        </p>
                        <div class="flex items-center">
                            <img class="w-10 h-10 rounded-full mr-4 bg-gray-200" src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ea6201&color=fff" alt="User">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Budi Santoso</h4>
                                <span class="text-xs text-gray-500">Entrepreneur</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative py-20 px-6 overflow-hidden" style="background-color: #0b2b2b;">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute right-0 top-0 w-64 h-64 bg-[#ea6201] rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute left-0 bottom-0 w-64 h-64 bg-[#ea6201] rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto text-center text-white">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Siap Mengambil Kendali <span class="text-[#ea6201]">Keuangan Anda?</span>
                </h2>
                <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto">
                    Bergabunglah sekarang dan mulai perjalanan menuju kebebasan finansial. Gratis untuk memulai, tanpa kartu kredit.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" 
                           class="inline-block bg-[#ea6201] hover:bg-[#c95401] text-white font-bold py-4 px-10 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-orange-500/30">
                            Daftar Sekarang Gratis
                        </a>
                    @endif
                    
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" 
                           class="inline-block bg-transparent border-2 border-white hover:bg-white hover:text-[#0b2b2b] text-white font-bold py-4 px-10 rounded-lg transition-all">
                            Masuk Akun
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <footer class="bg-black text-gray-400 py-8 px-10 text-center border-t border-gray-800 relative z-20">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <span class="text-white font-bold text-xl">Finance</span><span class="text-[#ea6201] font-bold text-xl">Hub</span>
                </div>
                <div class="text-sm">
                    &copy; {{ date('Y') }} Finance Hub. All rights reserved.
                </div>
            </div>
        </footer>

    </main>

    <script>
        const header = document.getElementById('main-header');

        function handleScroll() {
            const scrollThreshold = window.innerHeight * 0.15; // Trigger faster
            if (window.scrollY > scrollThreshold) {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
                header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.backgroundColor = 'transparent';
                header.style.boxShadow = 'none';
            }
        }

        function handleZoomAndResize() {
            const brandTextContainer = document.querySelector('.brand-text-container');
            const whiteText = document.querySelector('.brand-text-white');
            const orangeText = document.querySelector('.brand-text-orange');

            if (brandTextContainer && whiteText && orangeText) {
                // Force inline
                brandTextContainer.style.display = 'flex';
                brandTextContainer.style.alignItems = 'center';
                brandTextContainer.style.whiteSpace = 'nowrap';
                
                // Adjust font based on zoom level
                const zoomLevel = window.outerWidth / window.innerWidth;
                if (zoomLevel > 1.2) {
                    whiteText.style.fontSize = '0.9rem';
                    orangeText.style.fontSize = '0.9rem';
                } else {
                    whiteText.style.fontSize = '';
                    orangeText.style.fontSize = '';
                }
            }
            handleScroll();
        }

        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', handleZoomAndResize);
        
        let lastWidth = window.innerWidth;
        setInterval(function() {
            if (window.innerWidth !== lastWidth) {
                lastWidth = window.innerWidth;
                handleZoomAndResize();
            }
        }, 200);

        handleZoomAndResize();
    </script>

</body>
</html>