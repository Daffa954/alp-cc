<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Custom CSS for the hero content to align it properly on the left */
        .hero-content {
            max-width: 450px;
        }

        /* Custom class for the feature boxes, using a slightly darker teal/green for the background */
        .feature-box {
            background-color: #0b2b2b;
            /* A dark teal/green shade */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }

        /* Color utility for the icons, matching the orange theme */
        .theme-orange {
            color: #ea6201;
            /* Your theme color */
        }

        /* Make the body allow scrolling for the new section to be visible */
        body {
            overflow-y: auto !important;
            min-height: 200vh;
            /* Ensure there is scrollable content */
        }

        /* Prevent layout breaking on zoom */
        html {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        /* Ensure images are responsive */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Prevent horizontal scrolling */
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Keep Finance Hub text side by side at all zoom levels */
        .brand-text-container {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .brand-text {
            display: inline-block;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .hero-content {
                max-width: 100%;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            /* Prevent text from breaking on very small screens */
            .text-4xl {
                font-size: 1.75rem;
                line-height: 2.25rem;
            }

            .text-5xl {
                font-size: 2rem;
                line-height: 2.5rem;
            }
        }

        /* Force header items to stay in one row */
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

        /* Adjust button sizes for zoom */
        .nav-button {
            font-size: clamp(0.75rem, 2vw, 1rem);
            padding: 0.375rem 0.75rem;
        }

        /* Reduce logo size on very small screens */
        @media (max-width: 480px) {
            .header-logo-img {
                width: 24px !important;
                height: 24px !important;
            }

            .brand-text-white,
            .brand-text-orange {
                font-size: 0.875rem !important;
            }
        }

        /* For extreme zoom levels */
        @media (max-width: 360px) {
            .nav-button {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .brand-text-white,
            .brand-text-orange {
                font-size: 0.75rem !important;
            }
        }

        /* Ensure buttons don't wrap */
        .no-wrap-buttons {
            display: flex;
            flex-wrap: nowrap;
            white-space: nowrap;
        }
    </style>

</head>

<body class="bg-cover bg-center bg-no-repeat min-h-screen relative"
    style="background-image: url('{{ asset('img/landing0.jpg') }}'); background-attachment: fixed;">
    <div class="absolute inset-0 bg-black/70"></div>
    <br>
    <header id="main-header"
        class="fixed top-0 left-0 w-full py-2 px-3 sm:py-3 sm:px-4 md:px-6 lg:px-8 xl:px-12 z-50 text-white transition-colors duration-300 bg-transparent">
        <nav class="flex items-center justify-between w-full max-w-7xl mx-auto flex-nowrap">
            <!-- Logo Section - Made more compact -->
            <div class="header-logo-section">
                <img class="header-logo-img w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 object-contain flex-shrink-0 mr-2"
                    src="{{ asset('img/financeHubLogo.png') }}" alt="Logo FinanceHub">
                <div class="brand-text-container">
                    <span class="brand-text-white text-white text-sm sm:text-base md:text-lg font-bold flex-shrink-0">
                        Finance
                    </span>
                    <span
                        class="brand-text-orange text-[#ff6b00] text-sm sm:text-base md:text-lg font-bold flex-shrink-0 ml-1">
                        Hub
                    </span>
                </div>
            </div>

            <!-- Navigation Buttons - Made more compact -->
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

    <div class="h-16 sm:h-20 md:h-24 lg:h-28 w-full block"></div>


    <main class="relative z-10 overflow-hidden">
        <section class="py-20 px-10 md:px-20 lg:px-40 pb-80">
            <div class="text-white mt-5">
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
                    Dengan analisis cerdas, pelacakan pengeluaran, dan fitur perencanaan keuangan yang efisien,
                    anda bisa mendapatkan wawasan penting untuk membuat keputusan finansial yang lebih tepat dan
                    terarah.
                </p>

                <h3 class="text-2xl font-semibold text-center text-gray-800 mb-10">
                    Fitur Utama
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Melacak Finansial -->
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <!-- Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M17.778 8.222c-4.296-4.296-11.264-4.296-15.56 0a1 1 0 000 1.414c4.296 4.296 11.264 4.296 15.56 0a1 1 0 000-1.414zM4.331 15.65a8 8 0 0111.338 0l-.337.337a7 7 0 00-10.664 0l-.337-.337zM6.992 13.011a4 4 0 016.016 0l-.337.337a3 3 0 00-5.342 0l-.337-.337z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <p class="text-xl font-bold">Melacak Finansial</p>
                        </div>
                        <p class="text-gray-200">
                            Memantau pemasukan, pengeluaran, dan arus kas secara real-time untuk membantu anda memahami
                            kondisi keuangan secara jelas.
                        </p>
                    </div>

                    <!-- Rekomendasi Pengeluaran -->
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <!-- Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 2a8 8 0 00-8 8v5a1 1 0 001 1h14a1 1 0 001-1v-5a8 8 0 00-8-8zm-5 8a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <p class="text-xl font-bold">Rekomendasi Pengeluaran</p>
                        </div>
                        <p class="text-gray-200">
                            Memberikan saran pengeluaran yang lebih bijak berdasarkan kebiasaan finansial anda agar
                            finansial tetap terkendali.
                        </p>
                    </div>

                    <!-- Perencanaan Finansial -->
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <!-- Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M17.657 16.657L13.414 12.414A5 5 0 0014.243 11a5 5 0 10-7.07 0 5 5 0 00.829 1.414L3.343 16.657a1 1 0 001.414 1.414L8 13.414V17a1 1 0 102 0v-3.586l3.243 3.243a1 1 0 001.414-1.414zM10 8a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <p class="text-xl font-bold">Perencanaan Finansial</p>
                        </div>
                        <p class="text-gray-200">
                            Membantu anda menyusun rencana keuangan jangka pendek maupun panjang, seperti tabungan, dana
                            darurat, hingga tujuan investasi.
                        </p>
                    </div>

                    <!-- Cepat & Praktis -->
                    <div class="feature-box p-6 rounded-lg text-white">
                        <div class="flex items-center mb-2">
                            <span class="theme-orange mr-3">
                                <!-- Icon -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.393 2.029a1 1 0 01.765.347l6.5 6.5a1 1 0 01-1.414 1.414L12 4.414V15a1 1 0 11-2 0V4.414L3.75 10.39a1 1 0 11-1.414-1.414l6.5-6.5a1 1 0 011.057-.279z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <p class="text-xl font-bold">Cepat & Praktis</p>
                        </div>
                        <p class="text-gray-200">
                            Akses seluruh fitur keuangan dalam satu platform yang mudah digunakan, menghemat waktu anda
                            dalam mengelola keuangan.
                        </p>
                    </div>

                </div>

            </div>
        </section>

    </main>

    <script>
        // Get the header element
        const header = document.getElementById('main-header');

        // Function to check scroll position and toggle the class
        function handleScroll() {
            // Use viewport height instead of document height for better consistency
            const scrollThreshold = window.innerHeight * 0.25;
            const scrollPosition = window.scrollY;

            if (scrollPosition > scrollThreshold) {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
            } else {
                header.style.backgroundColor = 'transparent';
            }
        }

        // Update the JavaScript to handle zoom and resize
        function handleZoomAndResize() {
            // Force the Finance Hub text to stay side by side
            const brandTextContainer = document.querySelector('.brand-text-container');
            const whiteText = document.querySelector('.brand-text-white');
            const orangeText = document.querySelector('.brand-text-orange');

            if (brandTextContainer && whiteText && orangeText) {
                // Ensure they're inline and don't wrap
                brandTextContainer.style.display = 'flex';
                brandTextContainer.style.alignItems = 'center';
                brandTextContainer.style.whiteSpace = 'nowrap';
                brandTextContainer.style.flexWrap = 'nowrap';

                whiteText.style.display = 'inline-block';
                whiteText.style.whiteSpace = 'nowrap';

                orangeText.style.display = 'inline-block';
                orangeText.style.whiteSpace = 'nowrap';
                orangeText.style.marginLeft = '0.25rem';

                // Adjust font sizes for zoom levels
                const zoomLevel = window.outerWidth / window.innerWidth;
                if (zoomLevel > 1.5) {
                    // Large zoom - reduce font size to prevent wrapping
                    whiteText.style.fontSize = '0.8rem';
                    orangeText.style.fontSize = '0.8rem';
                } else if (zoomLevel > 1.2) {
                    // Medium zoom
                    whiteText.style.fontSize = '0.9rem';
                    orangeText.style.fontSize = '0.9rem';
                } else {
                    // Normal zoom - use responsive classes
                    whiteText.style.fontSize = '';
                    orangeText.style.fontSize = '';
                }
            }

            // Also handle scroll
            handleScroll();
        }

        // Attach event listeners
        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', handleZoomAndResize);

        // Use a MutationObserver to watch for zoom changes
        let lastWidth = window.innerWidth;
        let zoomCheck = setInterval(function() {
            if (window.innerWidth !== lastWidth) {
                lastWidth = window.innerWidth;
                handleZoomAndResize();
            }
        }, 100);

        // Initial call
        handleZoomAndResize();
    </script>

</body>

</html>
