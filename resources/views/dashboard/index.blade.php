<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('dashboard.index') }}" class="inline-flex items-center text-lg font-high text-white hover:text-blue-600 dark:text-white dark:hover:text-gray-400">Dashboard</a></u>  
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-4">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <div class="p-6 text-gray-900 dark:text-gray-100">
                                        {{ __("Summary") }}
                                    </div>
                                    <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">
                                                        SID
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Image
                                                    </th>
                                                    <th scope="col" class="px-6 py-4">
                                                        Product
                                                    </th>
                                                    
                                                </tr>
                                            </thead>
                                                    @forelse($sales as $sale) 
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <th class="px-6 py-3">
                                                        <x-input-label>{{ ++$i }}</x-input-label>
                                                    </th>
                                                   
                                                    <td class="px-6 py-4">
                                                        <x-input-label>{{ $sale->productname }}</x-input-label>
                                                        <x-input-label>Cab. No.: <b>{{ $sale->cabinetname }}</b></x-input-label>
                                                    </td>
                                                </tr>
                                                
                                                @empty
                                                <td scope="row" class="px-6 py-4">
                                                    No Records Found.
                                                </td>	
                                                @endforelse
                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('dashboard.partials.sales-table')
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('dashboard.partials.leesee-request-table')
            </div>
        </div>
    </div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('dashboard.partials.rental-payments-table')
            </div>
        </div>
    </div>
   

    
</x-app-layout>
