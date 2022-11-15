<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('graph.generate') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="wiki_url" :value="__('Wiki Url')" />

                <x-text-input id="wiki_url" class="block mt-1 w-full" type="text" name="wiki_url" :value="old('wiki_url')" required autofocus />

                <x-input-error :messages="$errors->get('wiki_url')" class="mt-2" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="justify-center">
                    {{ __('Get Data') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
