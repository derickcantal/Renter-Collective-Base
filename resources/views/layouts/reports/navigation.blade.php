<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
            {{ __('Sales') }}
        </x-nav-link>
        <x-nav-link :href="route('reportrequest.index')" :active="request()->routeIs('reportrequest.index')">
            {{ __('Requests') }}
        </x-nav-link>
        <x-nav-link :href="route('reportrental.index')" :active="request()->routeIs('reportrental.index')">
            {{ __('Rental Payments') }}
        </x-nav-link>
    </ul>
</div>
