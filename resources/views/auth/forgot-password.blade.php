<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <div class="text-center mb-8">
                <a href="/" class="inline-flex w-20 h-20 rounded-2xl bg-blue-700 shadow-sm items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.brand.logo_alt') }}" class="w-14 h-14 object-contain">
                </a>
                <h1 class="mt-5 text-4xl font-extrabold text-slate-950">YBMedicalClinic</h1>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-xl p-8 sm:p-10">
                <div class="mb-6">
                    <h2 class="text-3xl font-extrabold text-slate-950">{{ __('app.auth.reset_password') }}</h2>
                    <p class="mt-2 text-slate-500 leading-relaxed">
                        {{ __('app.auth.forgot_description') }}
                    </p>
                </div>

                <x-auth-session-status class="mb-5" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">{{ __('app.auth.email_address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border border-slate-300 rounded-xl py-4 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-xl font-extrabold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        {{ __('app.auth.email_password_reset_link') }}
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                    <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:text-blue-800">{{ __('app.auth.back_to_login') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
