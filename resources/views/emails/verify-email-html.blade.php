{{-- resources/views/emails/verify-email-html.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - FinanceHub</title>
    <style>
        /* CSS akan bekerja karena ini HTML murni */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
            padding: 20px;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: #000000;
            padding: 40px 20px;
            text-align: center;
        }
        
        .email-title {
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .email-subtitle {
            color: #f97316;
            font-size: 18px;
            font-weight: 500;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            color: #000000;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .user-name {
            color: #f97316;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            margin: 30px 0;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        
        .cta-button:hover {
            background: linear-gradient(135deg, #ea580c, #c2410c);
        }
        
        .link-container {
            background: #f8fafc;
            border-left: 4px solid #f97316;
            padding: 20px;
            border-radius: 6px;
            margin-top: 30px;
        }
        
        .link-text {
            color: #374151;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .verification-link {
            color: #ea580c;
            word-break: break-all;
            font-size: 13px;
            text-decoration: none;
        }
        
        .security-note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 15px;
            border-radius: 6px;
            margin-top: 30px;
            font-size: 14px;
        }
        
        .email-footer {
            background: #000000;
            color: #9ca3af;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        
        .footer-title {
            color: #f97316;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        @media (max-width: 600px) {
            .email-body {
                padding: 20px 15px;
            }
            
            .email-title {
                font-size: 24px;
            }
            
            .cta-button {
                padding: 14px 30px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1 class="email-title">FinanceHub</h1>
            <div class="email-subtitle">Smart Financial Analysis</div>
        </div>
        
        <div class="email-body">
            <h2 class="greeting">Halo <span class="user-name">{{ $user->name }}</span>!</h2>
            
            <p>Selamat datang di <strong>FinanceHub</strong> - platform analisis keuangan berbasis AI yang akan membantu Anda mengelola keuangan dengan lebih cerdas.</p>
            
            <p>Untuk mulai menggunakan semua fitur kami, verifikasi email Anda dengan klik tombol di bawah:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="cta-button">
                    🔓 VERIFIKASI EMAIL SAYA
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 10px;">
                    ⏰ Link berlaku 60 menit
                </p>
            </div>
            
            <div class="link-container">
                <p class="link-text"><strong>Atau salin link ini ke browser Anda:</strong></p>
                <a href="{{ $verificationUrl }}" class="verification-link">
                    {{ $verificationUrl }}
                </a>
            </div>
            
            <div class="security-note">
                ⚠️ <strong>Keamanan:</strong> Jangan bagikan link verifikasi ini kepada siapapun. 
                Tim FinanceHub tidak akan pernah meminta data verifikasi melalui telepon atau chat.
            </div>
        </div>
        
        <div class="email-footer">
            <div class="footer-title">FinanceHub</div>
            <p>Analisis Keuangan Cerdas</p>
            <p>© {{ date('Y') }} FinanceHub. All rights reserved.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                Email ini dikirim secara otomatis. Jika Anda tidak membuat akun, abaikan email ini.
            </p>
        </div>
    </div>
</body>
</html>