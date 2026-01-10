{{-- resources/views/auth/verify-email.blade.php --}}
<x-mail::message>
{{-- Custom Styles --}}
<style>
    .orange-divider {
        height: 4px;
        background: linear-gradient(90deg, #f97316, #ea580c);
        width: 80px;
        margin: 20px auto;
        border-radius: 2px;
    }
    .feature-icon {
        background: #fff7ed;
        border: 2px solid #fed7aa;
        border-radius: 10px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .feature-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin: 10px 0;
    }
</style>

{{-- Main Container --}}
<div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb;">

    {{-- Header --}}
    <div style="background: #000000; padding: 40px 30px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0 0 10px 0; font-size: 32px; font-weight: bold; letter-spacing: -0.5px;">
            🔐 VERIFIKASI EMAIL
        </h1>
        <div style="color: #f97316; font-size: 18px; font-weight: 500;">
            {{ config('app.name', 'Financial AI') }}
        </div>
        <div class="orange-divider"></div>
    </div>

    {{-- Content --}}
    <div style="padding: 40px 30px; color: #1f2937; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">

        {{-- Greeting --}}
        <div style="margin-bottom: 30px;">
            <h2 style="color: #000000; margin: 0 0 15px 0; font-size: 24px;">
                Halo, <span style="color: #ea580c;">{{ $user->name }}</span>!
            </h2>
            <p style="color: #4b5563; line-height: 1.6; margin: 0;">
                Selamat bergabung dengan <strong style="color: #000000;">{{ config('app.name') }}</strong> - 
                platform analisis keuangan yang akan membantu Anda mencapai tujuan finansial dengan lebih cerdas.
            </p>
        </div>

        {{-- Features --}}
        <div style="background: #f9fafb; border-radius: 10px; padding: 25px; margin: 30px 0; border: 1px solid #e5e7eb;">
            <h3 style="color: #000000; margin: 0 0 20px 0; font-size: 18px; display: flex; align-items: center;">
                <span style="background: #f97316; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">✓</span>
                Apa yang menunggu Anda?
            </h3>
            
            <div class="feature-item" style="display: flex; align-items: center;">
                <div class="feature-icon">📊</div>
                <div>
                    <strong style="color: #000000;">Analisis Pengeluaran Otomatis</strong>
                    <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Pantau pengeluaran dengan visualisasi yang jelas</p>
                </div>
            </div>
            
            <div class="feature-item" style="display: flex; align-items: center;">
                <div class="feature-icon">🤖</div>
                <div>
                    <strong style="color: #000000;">Rekomendasi AI Cerdas</strong>
                    <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Dapatkan saran penghematan berbasis AI</p>
                </div>
            </div>
            
            <div class="feature-item" style="display: flex; align-items: center;">
                <div class="feature-icon">📈</div>
                <div>
                    <strong style="color: #000000;">Laporan Mingguan/Bulanan</strong>
                    <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Tinjau perkembangan keuangan secara berkala</p>
                </div>
            </div>
        </div>

        {{-- CTA Button --}}
        <div style="text-align: center; margin: 40px 0;">
            <p style="color: #4b5563; margin-bottom: 20px; font-size: 15px;">
                Klik tombol di bawah untuk mengaktifkan akun Anda:
            </p>
            
            <x-mail::button :url="$verificationUrl" 
                style="background: linear-gradient(135deg, #f97316, #ea580c); 
                       border: none; 
                       padding: 16px 45px; 
                       font-size: 17px; 
                       font-weight: 600;
                       color: white;
                       border-radius: 8px;
                       text-decoration: none;
                       display: inline-block;
                       box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);">
                🔓 AKTIFKAN AKUN SAYA
            </x-mail::button>
            
            <p style="color: #9ca3af; font-size: 13px; margin-top: 15px;">
                ⏰ Link berlaku selama 60 menit
            </p>
        </div>

        {{-- Alternative Link --}}
        <div style="background: #f8fafc; border-left: 4px solid #f97316; padding: 20px; border-radius: 6px; margin-top: 30px;">
            <p style="color: #374151; margin: 0 0 10px 0; font-weight: 500; font-size: 14px;">
                📋 Atau salin link berikut ke browser Anda:
            </p>
            <div style="background: white; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb;">
                <a href="{{ $verificationUrl }}" 
                   style="color: #ea580c; 
                          text-decoration: none; 
                          word-break: break-all;
                          font-family: monospace;
                          font-size: 13px;">
                    {{ $verificationUrl }}
                </a>
            </div>
        </div>

        {{-- Security Note --}}
        <div style="margin-top: 30px; padding: 15px; background: #fffbeb; border-radius: 6px; border: 1px solid #fde68a;">
            <p style="color: #92400e; margin: 0; font-size: 13px; display: flex; align-items: flex-start;">
                <span style="color: #f97316; margin-right: 10px;">⚠️</span>
                <span><strong>Keamanan:</strong> Jangan bagikan link verifikasi ini kepada siapapun. 
                Tim {{ config('app.name') }} tidak akan pernah meminta data verifikasi melalui telepon atau chat.</span>
            </p>
        </div>

    </div>

    {{-- Footer --}}
    <div style="background: #000000; padding: 30px; text-align: center; border-top: 1px solid #374151;">
        
        {{-- Logo/App Name --}}
        <div style="margin-bottom: 20px;">
            <div style="color: #f97316; font-size: 22px; font-weight: bold; margin-bottom: 5px;">
                {{ config('app.name') }}
            </div>
            <div style="color: #9ca3af; font-size: 14px;">
                Smart Financial Analysis
            </div>
        </div>

        {{-- Links --}}
        <div style="margin-bottom: 25px;">
            <a href="{{ config('app.url') }}" 
               style="color: #d1d5db; text-decoration: none; margin: 0 15px; font-size: 14px;">
                Website
            </a>
            <span style="color: #4b5563;">•</span>
            <a href="{{ config('app.url') }}/privacy" 
               style="color: #d1d5db; text-decoration: none; margin: 0 15px; font-size: 14px;">
                Privasi
            </a>
            <span style="color: #4b5563;">•</span>
            <a href="{{ config('app.url') }}/help" 
               style="color: #d1d5db; text-decoration: none; margin: 0 15px; font-size: 14px;">
                Bantuan
            </a>
        </div>

        {{-- Copyright & Disclaimer --}}
        <div>
            <p style="color: #6b7280; font-size: 12px; margin: 0 0 10px 0; line-height: 1.5;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p style="color: #6b7280; font-size: 12px; margin: 0; line-height: 1.5;">
                Email ini dikirim secara otomatis. Jika Anda tidak membuat akun, abaikan email ini.
            </p>
        </div>

    </div>

</div>
</x-mail::message>