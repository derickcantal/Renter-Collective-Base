<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('mycabinet.index')" :active="request()->routeIs('mycabinet.index')">
            {{ __('Cabinet') }}
        </x-nav-link>
        <x-nav-link :href="route('myrequest.index')" :active="request()->routeIs('myrequest.index')">
            {{ __('Request') }}
        </x-nav-link>
        <x-nav-link :href="route('myrental.index')" :active="request()->routeIs('myrental.index')">
            {{ __('Rental') }}
        </x-nav-link>
    </ul>
</div>
