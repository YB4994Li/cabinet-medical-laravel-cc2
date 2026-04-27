<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-extrabold text-slate-900">Delete Account</h2>

        <p class="mt-1 text-sm text-slate-500 leading-relaxed">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-700">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <input id="password"
                       name="password"
                       type="password"
                       class="mt-1 block w-3/4 border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="{{ __('Password') }}">

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="submit" class="ms-3 bg-red-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-red-700">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
