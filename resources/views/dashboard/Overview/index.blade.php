<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.dashboard.navigation')
        </div>
    </div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="py-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("Overview") }}
                        </div>
                        
                        <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            TOTAL
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th class="px-6 py-4">
                                            <x-input-label>Today Sales</x-input-label>
                                        </th>
                                        <td class="px-6 py-4">
                                            <x-input-label>{{ number_format($totalsales, 2); }}</x-input-label>
                                        </td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th class="px-6 py-4">
                                            <x-input-label>Last 7 Days Total</x-input-label>
                                        </th>
                                        <td class="px-6 py-4">
                                            <x-input-label>{{ number_format(0, 2); }}</x-input-label>
                                        </td>
                                    </tr>
                 
                                    
                                </tbody>
                            </table>
                          
                        </div>
                        <div class="py-4">
                            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg" >
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                MONTHLY SALES
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                TOTAL
                                            </th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        @forelse($renter_monthly_sale as $renter_monthly_sales) 
                                        <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th class="px-6 py-4">
                                                <x-input-label>{{ $renter_monthly_sales->rpmonth }} - {{ $renter_monthly_sales->rpyear }}</x-input-label>
                                            </th>
                                            <td class="px-6 py-4">
                                                <x-input-label>{{ number_format($renter_monthly_sales->totalsales, 2); }}</x-input-label>
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
</x-app-layout>


