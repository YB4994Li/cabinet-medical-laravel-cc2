<section>
    <header>
        <h2 class="text-2xl font-extrabold text-slate-900">Profile Information</h2>
        <p class="mt-1 text-sm text-slate-500">
            Update your account details and profile picture.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-5 rounded-2xl bg-slate-50 border border-slate-200">
            @if($user->profile_photo_url)
                <img src="{{ $user->profile_photo_url }}"
                     alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover border border-slate-200 bg-white">
            @else
                <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-4xl font-extrabold border border-blue-200">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <div class="flex-1">
                <label for="profile_photo" class="block text-sm font-bold text-slate-700 mb-2">Profile Picture</label>
                <input id="profile_photo"
                       name="profile_photo"
                       type="file"
                       accept="image/*"
                       class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-700 file:px-4 file:py-3 file:font-bold file:text-white hover:file:bg-blue-800">
                <p class="text-xs text-slate-500 mt-2">Use a JPG, PNG, GIF, or WebP image up to 2 MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            </div>
        </div>

        <div>
            <label for="name" class="block text-sm font-bold text-slate-600 mb-2">Name</label>
            <input id="name"
                   name="name"
                   type="text"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name"
                   class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-600 mb-2">Email</label>
            <input id="email"
                   name="email"
                   type="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username"
                   class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-700">
                        Your email address is unverified.

                        <button form="send-verification" class="font-bold text-blue-700 hover:text-blue-800">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800">
                Save Profile
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >Saved.</p>
            @endif
        </div>
    </form>
</section>
