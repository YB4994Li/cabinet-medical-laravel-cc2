@php
    $currentLocale = app()->getLocale();
    $locales = [
        'en' => __('app.language.english'),
        'es' => __('app.language.spanish'),
    ];
@endphp

<div class="relative group">
    <button type="button"
            aria-label="{{ __('app.language.label') }}"
            class="h-10 min-w-10 px-3 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:border-blue-200 flex items-center justify-center gap-2 shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18m0 18a9 9 0 1 1 0-18m0 18c2.5-2.4 4-5.6 4-9s-1.5-6.6-4-9m0 18c-2.5-2.4-4-5.6-4-9s1.5-6.6 4-9M3.6 9h16.8M3.6 15h16.8" />
        </svg>
        <span class="text-xs font-extrabold uppercase">{{ $currentLocale }}</span>
    </button>

    <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition absolute right-0 mt-2 w-40 rounded-xl bg-white border border-slate-200 shadow-xl z-50 overflow-hidden">
        @foreach($locales as $locale => $label)
            <a href="{{ route('language.switch', ['locale' => $locale]) }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-bold {{ $currentLocale === $locale ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}"
               aria-label="{{ __('app.language.switch_to', ['language' => $label]) }}">
                <span>{{ $label }}</span>
                <span class="text-xs uppercase">{{ $locale }}</span>
            </a>
        @endforeach
    </div>
</div>
