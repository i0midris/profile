<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.auth_login_title') }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center p-4"
    style="background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #0f0f23 100%);">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl gradient-primary flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ __('admin.admin_panel') }}</h1>
            <p class="text-white/50 mt-2">{{ __('admin.sign_in_to_manage') }}</p>
        </div>

        <!-- Login Form -->
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-white/80 mb-2">{{ __('admin.email_address') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                        placeholder="{{ __('admin.placeholder_admin_email') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white/80 mb-2">{{ __('admin.password') }}</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                        placeholder="{{ __('admin.placeholder_password') }}">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
                    <label for="remember" class="ms-2 text-sm text-white/70">{{ __('admin.remember_me') }}</label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl font-semibold text-white gradient-primary hover:shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-0.5 transition-all duration-300">
{{ __('admin.sign_in') }}
                </button>
            </form>
        </div>

        <!-- Back to Site -->
        <div class="text-center mt-6">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="text-white/50 hover:text-white transition-colors text-sm">
                <span class="inline-block rtl-flip">←</span> {{ __('admin.back_to_website') }}
            </a>
        </div>
    </div>
</body>

</html>
