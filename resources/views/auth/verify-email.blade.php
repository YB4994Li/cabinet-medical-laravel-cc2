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
                    <h2 class="text-3xl font-extrabold text-slate-950">{{ __('app.auth.verify_email') }}</h2>
                    <p class="mt-2 text-slate-500 leading-relaxed">
                        {{ __('app.auth.verify_description') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">
                        {{ __('app.auth.verification_sent') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-xl font-extrabold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                            {{ __('app.auth.resend_verification') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-6 py-4 rounded-xl border border-slate-300 bg-white font-extrabold text-slate-700 hover:border-blue-300 hover:text-blue-700">
                            {{ __('app.auth.log_out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
