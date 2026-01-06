@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="bg-white card-shadow rounded-2xl p-8 border-[1px] border-slate-300">
    <!-- Logo/Header -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <div class="bg-[#ff6b00] w-16 h-16 rounded-2xl flex items-center justify-center">
                <i class="fas fa-envelope-circle-check text-white text-2xl"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-slate-800">Verify Your Email</h2>
        <p class="text-slate-600 mt-2">One more step to get started</p>
    </div>

    <!-- Message -->
    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-[#ff6b00] rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-[#ff6b00] mt-1"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-slate-700">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="space-y-6">
        <!-- Resend Verification Form -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" 
                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r bg-[#ff720c] hover:bg-[#fb6602] text-white font-semibold rounded-xl hover:shadow-lg transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-slate-500">Or</span>
            </div>
        </div>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                class="w-full flex items-center justify-center px-4 py-3 border border-slate-800 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 hover:border-slate-300 transition duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>
                {{ __('Log Out') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="pt-4 border-t border-slate-200">
            <p class="text-center text-sm text-slate-600">
                Already verified?
                <a href="{{ route('login') }}" class="font-semibold text-[#ff911b] hover:text-[#fb6602] transition">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Progress Steps -->
<div class="mt-8">
    <div class="flex items-center justify-center">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full border-2 border-[#f9b17e] flex items-center justify-center">
                <i class="fas fa-check text-[#ff9b53] text-xs"></i>
            </div>
            <div class="w-16 h-1 bg-[#f9b17e] mx-2"></div>
            <div class="bg-[#ff6b00] w-8 h-8 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope text-white text-xs"></i>
            </div>
            <div class="w-16 h-1 bg-[#f9b17e] mx-2"></div>
            <div class="w-8 h-8 rounded-full border-2 border-[#f9b17e] flex items-center justify-center">
                <span class="text-xs text-[#ff9b53]">3</span>
            </div>
        </div>
    </div>
    <div class="flex justify-between mt-2 text-xs text-slate-500 px-4">
        <span class="text-[#ff6b00] font-medium">Registration</span>
        <span class="text-[#ff6b00] font-medium">Verification</span>
        <span>Dashboard</span>
    </div>
</div>

<!-- Email Animation -->
<div class="mt-6 text-center">
    <div class="inline-block relative">
        <i class="fas fa-envelope text-4xl text-[#f8a163] animate-pulse"></i>
        <i class="fas fa-paper-plane text-xl text-[#ff6b00] absolute -top-2 -right-2 animate-bounce"></i>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add animation for the envelope
    document.addEventListener('DOMContentLoaded', function() {
        const envelope = document.querySelector('.fa-envelope');
        let scale = 1;
        let direction = 0.005;
        
        function pulse() {
            scale += direction;
            if (scale >= 1.1 || scale <= 0.9) {
                direction *= -1;
            }
            envelope.style.transform = `scale(${scale})`;
            requestAnimationFrame(pulse);
        }
        
        // Start subtle pulse animation
        pulse();
        
        // Auto-resend after 60 seconds if user is still on page
        setTimeout(() => {
            const resendBtn = document.querySelector('button[type="submit"]');
            if (resendBtn && !document.querySelector('.bg-green-50')) {
                console.log('Auto-resend reminder');
            }
        }, 60000);
    });
</script>
@endsection