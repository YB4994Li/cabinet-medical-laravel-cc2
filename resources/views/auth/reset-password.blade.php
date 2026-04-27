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
                    <h2 class="text-3xl font-extrabold text-slate-950">{{ __('app.auth.choose_new_password') }}</h2>
                    <p class="mt-2 text-slate-500">{{ __('app.auth.reset_description') }}</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">{{ __('app.auth.email_address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                               class="w-full border border-slate-300 rounded-xl py-4 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">{{ __('app.auth.password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full border border-slate-300 rounded-xl py-4 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">{{ __('app.auth.confirm_password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full border border-slate-300 rounded-xl py-4 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-xl font-extrabold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        {{ __('app.auth.reset_password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
