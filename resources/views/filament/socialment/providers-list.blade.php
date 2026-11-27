@php
    $panelId = $panel->getId();
@endphp
{{-- Shown above the email login form (see the render hook in AdminPanelProvider). --}}
<section class='filalite-panel flex flex-col gap-6 mb-4'>
    @if (! empty($errorMessage))
        <p class="text-sm text-center text-red-600" role="alert">{{ $errorMessage }}</p>
    @endif
    <div class='flex justify-center gap-x-4 p-2'>
        @foreach ($providers as $providerName => $provider)
            <a class='ring-2 ring-slate-700/50 hover:ring-slate-600/70 transition-all rounded-lg px-4 py-3 flex gap-2 items-center'
               href='{{ route('socialment.redirect.panel', ['panelId' => $panelId, 'provider' => $providerName]) }}'>
                {{-- svg() rather than <x-icon>: the panel switches Blade icon components off. --}}
                {{ svg($provider['icon'], 'w-8') }}
                <span>{{ $provider['label'] }}</span>
            </a>
        @endforeach
    </div>
    <div class="relative flex items-center">
        <div class="grow border-t border-gray-400"></div>
        <span class="shrink mx-4 text-gray-400 px-4">
            {{ config('socialment.view.prompt', 'Or login with email:') }}
        </span>
        <div class="grow border-t border-gray-400"></div>
    </div>
</section>
