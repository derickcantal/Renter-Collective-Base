<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('dashboard.overview.index')" :active="request()->routeIs('dashboard.overview.index')">
            {{ __('Summary') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboard.sales.index')" :active="request()->routeIs('dashboard.sales.index')">
            {{ __('Sales') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboard.requests.index')" :active="request()->routeIs('dashboard.requests.index')">
            {{ __('Requests') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboard.rental.index')" :active="request()->routeIs('dashboard.rental.index')">
            {{ __('Rental Payments') }}
        </x-nav-link>
    </ul>
</div>
